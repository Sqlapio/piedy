<?php

namespace App\Livewire;

use App\Models\CajaChica;
use App\Models\Gasto;
use App\Models\MovimientoCajaChica;
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

    public $referencia;

    public $buscar;
    public $ocultar_form_cliente = 'hidden';
    public $ocultar_table_cliente = '';

    public function ocultar_table()
    {
        $hoy = date('d-m-Y');

        $caja_chica = CajaChica::where('fecha', $hoy)->first();

        if($caja_chica == null)
        {
            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'La caja chica no ha sido aperturada. Debe cargar el monto incial.'
            );

        }else{

            $this->ocultar_table_cliente = 'hidden';
            $this->ocultar_form_cliente = '';

        }

    }

    public function store()
    {
        $this->validate();

        try {

            $user = Auth::user();
            $cc = CajaChica::where('fecha', date('d-m-Y'))->first();
            $gasto = new Gasto();
            $gasto->descripcion = $this->descripcion;
            $gasto->forma_pago = $this->forma_pago;

            if($this->referencia == '')
            {
                $gasto->referencia = 'No aplica';

            }else{
                $gasto->referencia = $this->referencia;

            }

            if($this->forma_pago == 'USD')
            {
                $gasto->monto_usd = str_replace(',', '.', str_replace('.', '', $this->monto));
            }
            if($this->forma_pago == 'BS'){

                $gasto->monto_bsd = str_replace(',', '.', str_replace('.', '', $this->monto));
            }

            $gasto->fecha = date('d-m-Y');
            $gasto->responsable = $user->name;
            $gasto->save();

            $mov_caja_chica = new MovimientoCajaChica();
            $mov_caja_chica->gasto_id       = $gasto->id;
            $mov_caja_chica->caja_chica_id  = $cc->id;
            $mov_caja_chica->saldo          = $cc->saldo - $gasto->monto_usd;
            $mov_caja_chica->fecha          = date('d-m-Y');
            $mov_caja_chica->responsable    = Auth::user()->name;
            $mov_caja_chica->save();

            /**
             * Actualizo el saldo de la caja chica
             */
            DB::table('caja_chicas')->where('id', $cc->id)->update(['saldo' => $mov_caja_chica->saldo]);

            Notification::make()
                ->title('El gasto fue registrado con éxito')
                ->success()
                ->send();

            $this->reset();

        } catch (\Throwable $th) {
            dd($th);
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
        return view('livewire.gastos', [
            'data' => Gasto::orderBy('id', 'desc')
                ->orWhere('descripcion', 'like', "%{$this->buscar}%")
                ->orWhere('forma_pago', 'like', "%{$this->buscar}%")
                ->orWhere('monto_usd', 'like', "%{$this->buscar}%")
                ->orWhere('monto_bsd', 'like', "%{$this->buscar}%")
                ->orWhere('referencia', 'like', "%{$this->buscar}%")
                ->paginate(5)
        ]);
    }
}
