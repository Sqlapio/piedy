<?php

namespace App\Livewire;

use App\Models\AsignarProducto as ModelsAsignarProducto;
use App\Models\Producto;
use App\Models\User;
use Filament\Notifications\Livewire\Notifications;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class AsignarProducto extends Component
{
    use Actions;

    #[Validate('required', message: 'campo requerido')]
    public $producto;

    #[Validate('required', message: 'campo requerido')]
    public $empleado;

    #[Validate('required', message: 'campo requerido')]
    public $cantidad;

    public $ocultar_table_productos = '';

    public $ocultar_form = 'hidden';

    public function ocultar_tabla()
    {
        $this->ocultar_table_productos = 'hidden';
        $this->ocultar_form = '';
    }

    public function store()
    {
        $this->validate();

        try {

            $productos = Producto::where('id', $this->producto)->first();

            $empleado = User::where('id', $this->empleado)->first();

            $asignar_prod = new ModelsAsignarProducto();
            $asignar_prod->cod_producto     = $productos->cod_producto;
            $asignar_prod->producto_id      = $productos->id;
            $asignar_prod->producto         = $productos->descripcion;
            $asignar_prod->cantidad         = $this->cantidad;
            $asignar_prod->contenido_neto   = $productos->contenido_neto;
            $asignar_prod->fecha_entrega    = date('d-m-Y');
            $asignar_prod->empleado_id      = $empleado->id;
            $asignar_prod->empleado         = $empleado->name;
            $asignar_prod->responsable      = Auth::user()->name;
            $asignar_prod->save();

            if ($asignar_prod->save()) {
                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('success')
                    ->body('El producto fue asignado con exito')
                    ->send();
            } else {
                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('Error al asiganr el prodcuto')
                    ->send();
                return redirect()->back()->withInput();
            }

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
        return view('livewire.asignar-producto', [
            'data' => ModelsAsignarProducto::orderBy('producto_id')->paginate(5)
        ]);
    }
}
