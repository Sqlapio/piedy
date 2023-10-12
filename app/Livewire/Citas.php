<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\Cliente;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Citas extends Component
{
    use WithPagination;
    
    public $fecha;
    public $hora;
    public $cliente_id;
    public $servicio_id;
    public $empleado_id;

    public $ocultar = 'hidden';

    /**
     * Reglas de validación para todos los campos del formulario
     */
    public function validateDataCliente()
    {
        $this->validate([
            'fecha'         => 'required',
            'hora'          => 'required',
            'cliente_id'    => 'required',
            'servicio_id'   => 'required',
            'empleado_id'   => 'required',
        ]);
    }

    protected $messages = [

        'fecha'         => 'Campo requerido',
        'hora'          => 'Campo requerido',
        'cliente_id'    => 'Campo requerido',
        'servicio_id'   => 'Campo requerido',
        'empleado_id'   => 'Campo requerido',

    ];

    public function store() 
    {
        $this->validateDataCliente();

        try {

            $user = Auth::user();

            $cita = new Cita();
            $cita->cod_cita = 'Pci-'.random_int(11111, 99999);
            $cita->fecha = $this->fecha;
            $cita->hora = $this->hora;
            $cita->cliente_id = $this->cliente_id;
            $cita->servicio_id = $this->servicio_id;
            $cita->empleado_id = $this->empleado_id;
            $cita->responsable = $user->id;
            $cita->save();

            Notification::make()
                ->title('Cita agendada con éxito')
                ->success()
                ->send();
            
            $this->reset();
            
            
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function render()
    {
        $date = date('Y-m-d');
        return view('livewire.citas', [
            'data' => Cita::where('fecha', $date)                                   
                ->paginate(5)
        ]);
    }
}
