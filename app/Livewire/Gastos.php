<?php

namespace App\Livewire;

use App\Models\CajaChica;
use App\Models\Gasto;
use App\Models\MovimientoCajaChica;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class Gastos extends Component
{
    use WithPagination;
    use Actions;

    #[Rule('required', message: 'Campo requerido')]
    public $descripcion;

    #[Rule('required', message: 'Campo requerido')]
    public $monto;

    #[Rule('required', message: 'Campo requerido')]
    public $forma_pago;

    public $numero_factura;
    public $fecha_factura;

    public $buscar;
    public $ocultar_form_cliente = 'hidden';
    public $ocultar_table_cliente = '';

    public function store()
    {
        $this->validate();

        try {

            $cc = CajaChica::where('fecha', date('d-m-Y'))->first();

            $gasto = new Gasto();
            $gasto->descripcion = $this->descripcion;
            $gasto->forma_pago = $this->forma_pago;

            /**
             * Logica para formar el correlativo de la factura.
             */
            if($this->numero_factura == ''){
                /**Busco el ultimo numero de factura generado por el sistema */
                $ultimo_correlativo = Gasto::where('numero_factura', 'like', '%Pcf-%')->latest()->first();

                if(isset($ultimo_correlativo))
                {
                    $parte_entera = intval(str_replace('Pcf-', '', $ultimo_correlativo->numero_factura));
                    $sum_correlativo = $parte_entera + 1;

                }else{
                    $sum_correlativo = 1;
                }

                $gasto->numero_factura = 'Pcf-'.str_pad($sum_correlativo, 6, '0', STR_PAD_LEFT);

            }else{
                $gasto->numero_factura = $this->numero_factura;
            }

            /**
             * Logica para cargar el monto segun el tipo de pago
             */
            if($gasto->forma_pago == 'usd'){
                $gasto->monto_usd = $this->monto;
            }else{
                $gasto->monto_bsd = $this->monto;
            }

            $gasto->fecha_carga = now()->format('d-m-Y');
            $gasto->fecha_factura = date('d-m-Y', strtotime($this->fecha_factura));
            $gasto->responsable = Auth::user()->name;
            $gasto->save();

            $this->reset();

            $this->dispatch('carga-gastos');

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('El gasto fue cargado de forma correcta.')
            ->send();


        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN DE ERROR')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }

    public function inicio(){
        redirect()->to('/dashboard');
    }

    public function citas(){
        redirect()->to('/citas');
    }

    public function clientes(){
        redirect()->to('/clientes');
    }

    public function cabinas(){
        redirect()->to('/cabinas');
    }

    public function productos(){
        redirect()->to('/productos');
    }

    public function servicios(){
        redirect()->to('/servicios');
    }

    public function render()
    {
        return view('livewire.gastos');
    }
}
