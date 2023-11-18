<?php

namespace App\Livewire;

use App\Models\Gasto;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Gastos extends Component
{
    use WithPagination;

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
        $this->ocultar_table_cliente = 'hidden';
        $this->ocultar_form_cliente = '';
    }

    public function store()
    {
        $this->validate();

        try {

            $user = Auth::user();

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

            Notification::make()
                ->title('El gasto fue registrado con éxito')
                ->success()
                ->send();

            $this->reset();

        } catch (\Throwable $th) {
            //throw $th;
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
