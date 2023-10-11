<?php

namespace App\Livewire;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;

class Clientes extends Component
{
    use WithPagination;

    public $nombre;
    public $apellido;
    public $cedula;
    public $email;
    public $telefono;
    public $direccion_corta;

    public $buscar;
    public $ocultar_form_cliente = 'hidden';
    public $ocultar_table_cliente = '';

    /**
     * Reglas de validación para todos los campos del formulario
     */
    public function validateDataCliente()
    {
        $this->validate([
            'nombre'            => 'required',
            'apellido'          => 'required',
            'cedula'            => 'required|numeric',
            'email'             => 'required|email|unique:clientes',
            'telefono'          => 'required',
            'direccion_corta'   => 'required'
        ]);
    }

    protected $messages = [

        'nombre'            => 'Campo requerido',
        'apellido'          => 'Campo requerido',
        'cedula'            => 'Campo requerido',
        'cedula.numeric'    => 'El campo solo acepta números',
        'email.email'       => 'Campo tipo email',
        'telefono'          => 'Campo requerido',
        'direccion_corta'   => 'Campo requerido'

    ];

    public function ocultar_table()
    {
        $this->ocultar_table_cliente = 'hidden';
        $this->ocultar_form_cliente = '';
    }

    public function store()
    {
        $this->validateDataCliente();

        try {

            $user = Auth::user();

            $cliente = new Cliente();
            $cliente->nombre = $this->nombre;
            $cliente->apellido = $this->apellido;
            $cliente->cedula = $this->cedula;
            $cliente->email = $this->email;
            $cliente->telefono = $this->telefono;
            $cliente->direccion_corta = $this->direccion_corta;
            $cliente->user_id = $user->id;
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


    public function render()
    {
        return view('livewire.clientes' , [
            'data' => Cliente::orderBy('id', 'desc')
                ->orWhere('nombre', 'like', "%{$this->buscar}%")
                ->orWhere('apellido', 'like', "%{$this->buscar}%")
                ->orWhere('cedula', 'like', "%{$this->buscar}%")                                      
                ->paginate(5)
        ]);
    }
}
