<?php

namespace App\Livewire;

use App\Http\Controllers\LogController;
use App\Models\Cita;
use Illuminate\Support\Facades\Auth;
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

        try {

            DB::table('tasa_bcvs')
              ->where('id', 1)
              ->update([
                'tasa'  => $this->tasa,
                'fecha' => now()->format('d-m-Y')
            ]);

            /**Logica que limpia las citas del dia anterior para evitar el colapso de la agenda */
            $clean_citas = Cita::where('fecha_formateada', '<', date('Y-m-d'))->get();

            foreach ($clean_citas as $value) {
                $value->update([
                    'status' => '2'
                ]);
            }

            LogController::log(Auth::user()->id, 'Actualiza tasa BCV','El usuario actualizo la tasa BCV', $response = null);

            $this->forceClose()->closeModal();

            redirect()->to('/dashboard');
            //code...
        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function render()
    {
        return view('livewire.tasa-bcv');
    }
}