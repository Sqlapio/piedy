<?php

namespace App\Livewire;

use App\Filament\Resources\VentaProductoResource;
use App\Http\Controllers\UtilsController;
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

class VentaProducto extends Component
{
    use Actions;

    use WithPagination;

    public $buscar;
    public $productos_adicionales = [];
    public $total_productos = [];
    public $cliente_id;

    public $hidden = '';
    public $tableProductos = '';

    public $codigoAsignacion;

    public $metodoUsd;
    public $metodoBsd;
    public $montoUsd;
    public $montoBsd;

    public $referenciaUsd;
    public $referenciaBsd;
    public $nroTarjeta;


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

    public function mount()
    {
        $this->codigoAsignacion = 'Pca-'.random_int(11111111, 99999999);
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

        try {

            /**METODO USD */
            if($this->metodoUsd != '' && $this->metodoBsd == '')
            {
                /** Validacion para pago en Zelle */
                if($this->metodoUsd == 'Zelle'){
                    $validated = Validator::make(
                        // Data to validate...
                        ['referenciaUsd' => $this->referenciaUsd],
                        // Validation rules to apply...
                        [
                            'referenciaUsd' => 'required',
                        ],
                        // Custom validation messages...
                        [
                            'required'  => 'Referencia Zelle Requerida',
                        ],
                    )->validate();
                }

                $res = UtilsController::facturarProducto_usd($this->metodoUsd, $this->montoUsd, $this->codigoAsignacion);

                if($res){

                    CarProducto::truncate();

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

                $res = UtilsController::facturarProducto_bsd($this->metodoBsd, $this->montoBsd, $this->codigoAsignacion, $this->referenciaBsd, $this->nroTarjeta);

                if($res){

                    CarProducto::truncate();

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
                /** Validacion para pago en Zelle */
                if($this->metodoUsd == 'Zelle'){
                    $validated = Validator::make(
                        // Data to validate...
                        ['referenciaUsd' => $this->referenciaUsd],
                        // Validation rules to apply...
                        [
                            'referenciaUsd' => 'required',
                        ],
                        // Custom validation messages...
                        [
                            'required'  => 'Campo Requerido',
                        ],
                    )->validate();
                }

                /**Validacion para pagos con referencia en Bolivares */
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
                            'required'  => 'Campo Requerido',
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
                            'required'  => 'Campo Requerido',
                        ],
                    )->validate();
                }

                $res = UtilsController::facturarProducto_multiple($this->montoUsd, $this->montoBsd, $this->metodoUsd, $this->metodoBsd, $this->codigoAsignacion, $this->referenciaUsd, $this->referenciaBsd, $this->nroTarjeta);

                if($res){

                    CarProducto::truncate();

                    $this->reset();

                    $this->dispatch('truncate-item-car');

                    $this->notification_success('La venta se realizo de forma exitosa');

                }else{
                    $this->notification_warning('Ocurrio un error al cargar la venta, por favor comuniquese con el administrador');
                }

            }
            //code...
        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
        }


    }

    public function render(Request $request)
    {
        return view('livewire.venta-producto');
    }
}
