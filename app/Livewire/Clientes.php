<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\FichaMedica;
use App\Models\Frecuencia;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use WireUi\Traits\Actions;

class Clientes extends Component
{
    use WithPagination;

    use Actions;

    #[Validate('required', message: 'Nombre requerido')]
    public $nombre;

    #[Validate('required', message: 'Apellido requerido')]
    public $apellido;

    #[Validate('required', message: 'El número de cedula es requido')]
    public $cedula;

    public $email;

    public $telefono;

    /**Propiedades de la encuenta */
    /** Antecedentes Medicos */
    public $am_p1;
    public $am_p2;
    public $am_p3;
    public $am_p4;
    /** Historial Podologico */
    public $hp_p1;
    public $hp_p2;
    /** Estilo de Vida */
    public $ev_p1;
    public $ev_p2;
    public $ev_p3;
    public $comentario_adicional;

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
            $cliente->cedula      = $this->cedula;
            if(Cliente::where('email', $this->email)->exists())
            {
                throw new Exception("El email que intenta registrar ya se encuentra en nuestra base de datos. Por favor intente con otro");
            }
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

            /** Logica para cargar los datos de la encuesta despues de guardar el cliente nuevo */
            $ficha_medica = new FichaMedica();
            $ficha_medica->am_p1 = $this->am_p1;
            $ficha_medica->am_p2 = $this->am_p2;
            $ficha_medica->am_p3 = $this->am_p3;
            $ficha_medica->am_p4 = $this->am_p4;
            $ficha_medica->hp_p1 = $this->hp_p1;
            $ficha_medica->hp_p2 = $this->hp_p2;
            $ficha_medica->ev_p1 = $this->ev_p1;
            $ficha_medica->ev_p2 = $this->ev_p2;
            $ficha_medica->ev_p3 = $this->ev_p3;
            $ficha_medica->comentario_adicional = $this->comentario_adicional;
            $ficha_medica->cliente_id = $cliente->id;
            $ficha_medica->save();


            Notification::make()
                ->title('Cliente creado con éxito')
                ->success()
                ->send();

            $this->reset();

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->color('primary')
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
