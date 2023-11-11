<?php

namespace App\Livewire;

use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\TasaBcv;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class FacturarCliente extends Component
{
    use WithPagination;

    public $servicios = [];
    public $total_vista;
    public $total_vista_bsd;
    public $buscar;

    public $op1;
    public $op2;
    public $valor_uno;
    public $valor_dos;

    #[Rule('required')]
    public $referencia;

    public $descripcion;
    public $op1_hidden = 'hidden';
    public $op2_hidden = 'hidden';
    public $ref_hidden = 'hidden';

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

    public function facturar_cliente(){
        redirect()->to('/servicios');
    }

    public function total()
    {
        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $user = Auth::user();
        $data = Disponible::where('empleado_id', $user->id)->where('status', 'activo')->first();

        $valores = [];

        for ($i=0; $i < count($this->servicios) ; $i++) {
            $costo = Disponible::where('id', $this->servicios[$i])->first()->costo;
            array_push($valores, $costo);
        }

        $this->total_vista = array_sum($valores);
        $this->total_vista_bsd = $this->total_vista * $tasa_bcv;

    }

    public function render()
    {
        return view('livewire.facturar-cliente',[
            'data' => Disponible::Where('status', 'por facturar')
            ->Where('cliente', 'like', "%{$this->buscar}%")   
            ->orderBy('id', 'desc')
               ->paginate(4)
       ]);
    }
}
