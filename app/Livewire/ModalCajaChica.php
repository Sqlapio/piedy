<?php

namespace App\Livewire;


use App\Models\CajaChica as ModelsCajaChica;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;

class ModalCajaChica extends ModalComponent
{

    use Actions;

    #[Rule('required')]
    public $monto;

    protected $messages = [
        'required' => 'Campo requerido',
    ];

    public function actualiza_caja_chica()
    {

        try {

            $query = ModelsCajaChica::where('fecha', date('d-m-Y'))->first();

            if($query){
                DB::table('caja_chicas')
                ->where('id', $query->id)
                ->update([
                    'monto'         => $this->monto,
                    'saldo'         => $this->monto,
                    'responsable'   => Auth::user()->name,
                ]);

            }else{
                $caja_chica_apr = new ModelsCajaChica();
                $caja_chica_apr->monto = $this->monto;
                $caja_chica_apr->saldo = $this->monto;
                $caja_chica_apr->fecha = date('d-m-Y');
                $caja_chica_apr->responsable = Auth::user()->name;
                $caja_chica_apr->save();

            }

            $this->forceClose()->closeModal();

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body('El saldo de la caja chica fue actualizado con éxito')
            ->send();

            redirect()->to('/gastos');

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN DE ERRROR')
            ->icon('heroicon-o-shield-check')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    public function render()
    {
        return view('livewire.modal-caja-chica');
    }
}
