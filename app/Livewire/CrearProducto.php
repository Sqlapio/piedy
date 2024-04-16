<?php

namespace App\Livewire;

use App\Models\AsignarProducto;
use App\Models\Producto;
use App\Models\User;
use Filament\Notifications\Livewire\Notifications;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class CrearProducto extends Component
{
    use Actions;

    #[Validate('required', message: 'La descripción es requerida')]
    public $descripcion;

    #[Validate('required', message: 'Debe seleccionar una categoria')]
    public $categoria;

    #[Validate('required', message: 'La exitencia total es requerida')]
    public $existencia;

    #[Validate('required', message: 'campo requerido')]
    public $contenido_neto;

    #[Validate('required', message: 'campo requerido')]
    public $unidad;

    /**Propiedades para asignar el producto */
    public $producto;
    public $empleado;
    public $cantidad;

    public $ocultar_table_productos = '';

    public $ocultar_form_asignar = 'hidden';

    public $ocultar_form = 'hidden';

    public function ocultar_table()
    {
        $this->ocultar_table_productos = 'hidden';
        $this->ocultar_form = '';
    }

    public function formulario_asignar()
    {
        $this->ocultar_form_asignar = '';
        $this->ocultar_table_productos = 'hidden';
    }

    public function store()
    {

        $this->validate();

        $crear_prod = new Producto();
        $crear_prod->cod_producto   = 'Pprd-'.random_int(11111, 99999);
        $crear_prod->descripcion    = $this->descripcion;
        $crear_prod->categoria_id   = $this->categoria;
        $crear_prod->existencia     = $this->existencia;
        $crear_prod->contenido_neto = $this->contenido_neto;
        $crear_prod->unidad         = $this->unidad;
        $crear_prod->fecha_carga    = date('d-m-Y');
        $crear_prod->responsable    = Auth::user()->name;

        if($crear_prod->save()){
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('success')
                    ->body('El producto fue creado con exito')
                    ->send();

            $this->reset();

            return redirect('/productos/crear');

        }else{
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('El producto fue creado con exito')
                    ->send();

            return redirect()->back()->withInput();
        }



    }

    public function asignar()
    {
        try {

            $productos = Producto::where('id', $this->producto)->first();

            $empleado = User::where('id', $this->empleado)->first();

            $asignar_prod = new AsignarProducto();
            $asignar_prod->cod_producto     = $productos->cod_producto;
            $asignar_prod->producto_id      = $this->producto;
            $asignar_prod->cantidad         = $this->cantidad;
            $asignar_prod->fecha_entrega    = date('d-m-Y');
            $asignar_prod->user_id          = $this->empleado;
            $asignar_prod->responsable      = Auth::user()->name;
            $asignar_prod->save();

            if ($asignar_prod->save()) {

                $existencia = Producto::where('id', $asignar_prod->producto_id)->first()->existencia;

                $nueva_exitencia = $existencia - $asignar_prod->cantidad;

                if($nueva_exitencia <= 5){

                    DB::table('productos')->where('id', $asignar_prod->producto_id)->update(['existencia' => $nueva_exitencia]);

                    Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('success')
                    ->body('El producto se esta agontando en el inventario. Por favor informar al Gerente de la tienda')
                    ->send();

                }else{
                    DB::table('productos')->where('id', $asignar_prod->producto_id)->update(['existencia' => $nueva_exitencia]);

                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-shield-check')
                        ->iconColor('success')
                        ->body('El producto fue asignado con exito')
                        ->send();

                    }

                $this->reset();

                return redirect('/productos/crear');


            } else {
                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('Error al asiganr el prodcuto')
                    ->send();

                return redirect()->back()->withInput();
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.crear-producto');
    }
}
