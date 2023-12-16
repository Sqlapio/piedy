<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
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

        /** Consulta DB local */
        $tasa_actualizada = ModelsTasaBcv::first();


        if(false)
        {
            $this->forceClose()->closeModal();

            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'La tasa fue actualizada el dia de hoy, no puede repetir esta acción.'
            );

        }else{

            try {

                DB::connection('mysql_online')->getPDO();

                /** Guardo la data en la BD LOCAL */
                DB::table('tasa_bcvs')->where('id', 1)
                    ->update([
                        'tasa'  => $this->tasa,
                        'fecha' => $hoy
                    ]);

                /** Guardo en la DB:ONLINE la nueva informacion
                 * que es la agregada en la consulta anterior
                 */
                DB::connection('mysql_online')->table('tasa_bcvs')
                    ->where('id', 1)
                    ->update([
                        'tasa'  => $this->tasa,
                        'fecha' => $hoy
                    ]);

                $this->forceClose()->closeModal();

            } catch (\Throwable $th) {

                /** offline solo escribimos en la BD local */
                DB::table('tasa_bcvs')
                    ->where('id', 1)
                    ->update([
                        'tasa'  => $this->tasa,
                        'fecha' => $hoy
                    ]);

                $this->forceClose()->closeModal();

            }

        }

    }
    
    public function render()
    {
        return view('livewire.tasa-bcv');
    }
}
