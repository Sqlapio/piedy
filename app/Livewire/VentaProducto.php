<?php

namespace App\Livewire;

use App\Filament\Resources\VentaProductoResource;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\VentaProductoController;
use App\Models\Cliente;
use App\Models\CarProducto;
use App\Models\Producto;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaProducto as ModelsVentaProducto;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;

class VentaProducto extends Component
{
    use Actions;

    use WithPagination;

    public $buscar;
    public $productos_adicionales = [];
    public $total_productos = [];

    #[Validate('required', message: 'Debe seleccionar el cliente')]
    public $cliente_id;

    public $empleado_id;

    public $hidden = '';
    public $tableProductos = '';
    public $codigoAsignacion;
    public $metodoUsd;
    public $metodoBsd;

    #[Validate('required', message: 'Debe cargar el monto')]
    public $montoUsd;

    public $montoBsd;
    public $referenciaUsd;
    public $referenciaBsd;
    public $nroTarjeta;

    public $add_servicio = null;


    public function updateProperty()
    {
        if($this->hidden == ''){
            $this->hidden = 'hidden';
            $this->tableProductos = 'hidden';

        }else{
            $this->hidden = '';
            $this->tableProductos = '';
        }
    }

    public function notification_warning(string $mensage)
    {
        Notification::make()
        ->title('NOTIFICACIÓN')
        ->icon('heroicon-c-megaphone')
        ->iconColor('warning')
        ->color('warning')
        ->body($mensage)
        ->send();
    }

    public function notification_success(string $mensage)
    {
        Notification::make()
        ->title('NOTIFICACIÓN')
        ->icon('heroicon-c-megaphone')
        ->iconColor('success')
        ->color('success')
        ->body($mensage)
        ->send();
    }

    public function calculo(Request $request)
    {
        $compraUsd = CarProducto::sum('total_compra_usd');

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $this->montoBsd = ($compraUsd - $this->montoUsd) * $tasa_bcv;

        $this->montoBsd = number_format(($this->montoBsd), 2, ",", ".");

    }

    public function facturar_producto()
    {
        $this->validate();

        /**METODO USD */
        if($this->metodoUsd != '' && $this->metodoBsd == '')
        {
            /** Validacion para pago en Zelle */
            if($this->metodoUsd == 'Zelle'){
                $validated = Validator::make(
                    [
                        'referenciaUsd' => $this->referenciaUsd
                    ],
                    [
                        'referenciaUsd' => 'required',
                    ],
                    [
                        'required'  => 'Referencia Zelle Requerida',
                    ],
                )->validate();

            }

            $res = VentaProductoController::facturarProducto_usd($this->metodoUsd, $this->montoUsd, $this->cliente_id, $this->empleado_id);

            if($res){

                CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                $this->reset();

                $this->dispatch('truncate-item-car');

                $this->notification_success('La venta se realizo de forma exitosa');

            }else{
                $this->notification_warning('El monto debe ser igual al total de la compra. Por favor valide y vuelva a intentarlo');
            }

        }

        /**METODO BSD */
        if($this->metodoBsd != '' && $this->metodoUsd == '')
        {
                /** Validacion para pago con bolivares */
            if($this->metodoBsd != 'Punto de venta'){
                $validated = Validator::make(
                    // Data to validate...
                    ['referenciaBsd' => $this->referenciaBsd],
                    // Validation rBles to apply...
                    [
                        'referenciaBsd' => 'required',
                    ],
                    // Custom validation messages...
                    [
                        'required'  => 'Referencia de pago Requerida',
                    ],
                )->validate();
            }else{
                $validated = Validator::make(
                    // Data to validate...
                    [
                        'referenciaBsd' => $this->referenciaBsd,
                        'nroTarjeta' => $this->nroTarjeta
                    ],
                    // Validation rules to apply...
                    [
                        'referenciaBsd' => 'required',
                        'nroTarjeta' => 'required',
                    ],
                    // Custom validation messages...
                    [
                        'required'  => 'Debe llenar los campos referencia(Bs.) y Nro. de Tarjeta',
                    ],
                )->validate();
            }

            $res = VentaProductoController::facturarProducto_bsd($this->metodoBsd, $this->montoBsd, $this->referenciaBsd, $this->nroTarjeta, $this->cliente_id, $this->empleado_id);

            if($res){

                CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                $this->reset();

                $this->dispatch('truncate-item-car');

                $this->notification_success('La venta se realizo de forma exitosa');

            }else{
                $this->notification_warning('El monto debe ser igual al total de la compra. Por favor valide y vuelva a intentarlo');
            }
        }

        /**METODO MULTIPLE - USD + BSD */
        if($this->metodoUsd != '' && $this->metodoBsd != '')
        {
                /** Validacion para pago con bolivares */
            if($this->metodoBsd != 'Punto de venta'){
                $validated = Validator::make(
                    // Data to validate...
                    ['referenciaBsd' => $this->referenciaBsd],
                    // Validation rBles to apply...
                    [
                        'referenciaBsd' => 'required',
                    ],
                    // Custom validation messages...
                    [
                        'required'  => 'Referencia de pago Requerida',
                    ],
                )->validate();
            }else{
                $validated = Validator::make(
                    // Data to validate...
                    [
                        'referenciaBsd' => $this->referenciaBsd,
                        'nroTarjeta' => $this->nroTarjeta
                    ],
                    // Validation rules to apply...
                    [
                        'referenciaBsd' => 'required',
                        'nroTarjeta' => 'required',
                    ],
                    // Custom validation messages...
                    [
                        'required'  => 'Debe llenar los campos referencia(Bs.) y Nro. de Tarjeta',
                    ],
                )->validate();
            }

            $res = VentaProductoController::facturarProducto_multiple($this->montoUsd, $this->montoBsd, $this->metodoUsd, $this->metodoBsd, $this->referenciaUsd, $this->referenciaBsd, $this->nroTarjeta, $this->cliente_id, $this->empleado_id);

            if($res){

                CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->delete();

                $this->reset();

                $this->dispatch('truncate-item-car');

                $this->notification_success('La venta se realizo de forma exitosa');

            }else{
                $this->notification_warning('Ocurrio un error al cargar la venta, por favor comuniquese con el administrador');
            }

        }

    }

    public function render(Request $request)
    {
        return view('livewire.venta-producto');
    }
}