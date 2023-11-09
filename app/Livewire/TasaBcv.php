<?php

namespace App\Livewire;

use App\Models\TasaBcv as ModelsTasaBcv;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;

class TasaBcv extends ModalComponent
{
    use Actions;
    
    #[Rule('required')]
    public $tasa;

    protected $messages = [
        'nombre' => 'Campo requerido',
    ];

    public function actualiza_tasa() 
    {

        $hoy = date('d-m-Y'); 

        $tasa_actualizada = ModelsTasaBcv::first();

        if($tasa_actualizada->fecha == $hoy)
        {
            $this->forceClose()->closeModal();

            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'La tasa fue actualizada el dia de hoy, no puede repetir esta acción.'
            );

        }else{

            DB::table('tasa_bcvs')
              ->where('id', 1)
              ->update([
                'tasa'  => $this->tasa,
                'fecha' => $hoy
            ]);

            $this->forceClose()->closeModal();

            // $this->dialog()->success(
            //     $title = 'Exito !!!',
            //     $description = 'La tasa fue actualizada de forma exitosa.'
            // );

            redirect()->to('/dashboard');

        }
        

    }
    public function render()
    {
        return view('livewire.tasa-bcv');
    }
}
