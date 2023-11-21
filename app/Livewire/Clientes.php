<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Livewire\Attributes\Rule;

class Clientes extends Component
{
    use WithPagination;

    #[Rule('required', message: 'Nombre requerido')]
    public $nombre;

    public $apellido;

    public $email;
    public $telefono;

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

            $cliente = new Cliente();
            $cliente->nombre = strtoupper($this->nombre);
            $cliente->apellido = strtoupper($this->apellido);
            $cliente->email = $this->email;
            $cliente->telefono = $this->telefono;
            $cliente->user_id = $user->id;
            $cliente->responsable= $user->name;
            $cliente->save();

            Notification::make()
                ->title('Cliente creado con éxito')
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
        return view('livewire.clientes' , [
            'data' => Cliente::orderBy('id', 'desc')
                ->orWhere('nombre', 'like', "%{$this->buscar}%")
                ->orWhere('apellido', 'like', "%{$this->buscar}%")
                ->paginate(5)
        ]);
    }
}
