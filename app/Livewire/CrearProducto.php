<?php

namespace App\Livewire;

use App\Models\Producto;
use Filament\Notifications\Livewire\Notifications;
use Livewire\Component;
use WireUi\Traits\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
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

    public $ocultar_table_productos = '';

    public $ocultar_form = 'hidden';

    public function ocultar_table()
    {
        $this->ocultar_table_productos = 'hidden';
        $this->ocultar_form = '';
    }

    public function asignar_servicio()
    {
        return redirect('/productos/asignar');
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
                    return redirect()->back();
        }else{
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('El producto fue creado con exito')
                    ->send();
            return redirect()->back()->withInput();
        }

        $this->reset();

    }

    public function render()
    {
        return view('livewire.crear-producto', [
            'data' => Producto::orderBy('descripcion')->paginate(5)
        ]);
    }
}
