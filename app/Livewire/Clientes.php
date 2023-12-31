<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Frecuencia;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;

class Clientes extends Component
{
    use WithPagination;

    use Actions;

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
        $this->info();
        $this->ocultar_table_cliente = 'hidden';
        $this->ocultar_form_cliente = '';
    }

    public function store()
    {
        $this->validate();

        try {

            $user = Auth::user();

            $cliente = new Cliente();
            $cliente->nombre      = strtoupper($this->nombre);
            $cliente->apellido    = strtoupper($this->apellido);
            $cliente->email       = $this->email;
            $cliente->telefono    = $this->telefono;
            $cliente->user_id     = $user->id;
            $cliente->responsable = $user->name;
            $cliente->save();

            /** El nuevo cliente es cargado en la tabla de frecuencias
             * para fines estadisticos
             */
            $cliente_nuevo = new Frecuencia();
            $cliente_nuevo->cliente_id  = $cliente->id;
            $cliente_nuevo->nombre      = strtoupper($cliente->nombre.' '.$cliente->apellido);
            $cliente_nuevo->save();

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

    public function info()
    {
        $this->dialog([
            'title'       => 'INFORMACION IMPORTANTE!!!',
            'description' => 'Debes cargar el correo electronico valido de cada cliente que registres, para poder enviar las notificaciones de promociones y ofertas en productos y servicios.',
            'icon'        => 'success'
        ]);
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
