<?php

namespace App\Livewire;

use App\Models\Empleado;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class Empleados extends Component
{

    use WithPagination;

    use Actions;

    #[Rule('required', message: 'Nombre requerido')]
    public $name;

    #[Rule('required', message: 'Email requerido')]
    public $email;

    #[Rule('required', message: 'Campo requerido')]
    public $area_trabajo;

    #[Rule('required', message: 'Campo requerido')]
    public $tipo_servicio_id;

    public $tipo_usuario;

    #[Rule('required', message: 'Campo requerido')]
    public $password;

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

            $empleado = new User();
            $empleado->name             = ucfirst($this->name);
            $empleado->email            = $this->email;
            $empleado->password         = Hash::make($this->password);
            $empleado->tipo_usuario     = 'empleado';
            $empleado->area_trabajo     = $this->area_trabajo;
            $empleado->tipo_servicio_id = $this->tipo_servicio_id;
            $empleado->save();

            Notification::make()
                ->title('Empleado creado con éxito')
                ->success()
                ->send();

            $this->reset();

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function eliminar_empleado($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Estás seguro?',
            'description' => 'Esta accion no podra ser reversada',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Si, eliminar empleado',
                'method' => 'eliminar',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'No, cancelar',
                'method' => 'cancelar',
            ],
        ]);
    }

    public function eliminar($id)
    {
        User::where('id', $id)->delete();
        $this->dialog([
            'title'       => 'Exito!',
            'description' => 'El empleado fue eliminado de forma satisfactoria.',
            'icon'        => 'success'
        ]);
    }

    /**ACCIONES PARA USO DEL MENU EN EL MODULO DE NOMINA */
    /************************************************** */
    public function accion($value)
    {
        if($value == 1){
            $this->redirect('/n/q');
        }
        if($value == 2){
            $this->redirect('/n/m');
        }
        if($value == 3){
            $this->redirect('/n/q');
        }
        if($value == 4){
            $this->redirect('/n/e');
        }
        if($value == 5){
            $this->redirect('/empleados');
        }
    }
    /************************************************** */

    public function cancelar()
    {
        $this->reset();
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
            'description' => 'Debes cargar el correo electronico valido de cada emplado que registres, para poder enviar las notificaciones al cerrar los servicios.',
            'icon'        => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.empleados', [
            'data' => User::where('tipo_usuario', 'empleado')
                ->Where('name', 'like', "%{$this->buscar}%")
                ->orderBy('id', 'asc')
                ->paginate(8)
        ]);
    }
}
