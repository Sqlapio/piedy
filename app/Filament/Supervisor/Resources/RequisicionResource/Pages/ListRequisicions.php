<?php

namespace App\Filament\Supervisor\Resources\RequisicionResource\Pages;

use Filament\Actions;
use App\Models\Requisicion;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Supervisor\Resources\RequisicionResource;

class ListRequisicions extends ListRecords
{
    protected static string $resource = RequisicionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Generar Requisicion')
                ->color('success')
                ->icon('heroicon-o-plus')
                ->requiresConfirmation()
                ->action(function (Requisicion $requisicion) {
                    try {
                        $requisicion = Requisicion::create([
                            'codigo'        => 'REQ-' . rand(111111, 999999),
                            'sucursal_id'   => Auth::user()->sucursal_id,
                            'user_id'       => Auth::user()->id,
                            'fecha'         => now()->format('d-m-Y'),
                        ]);
                        if ($requisicion) {
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-c-x-circle')
                                ->color('success')
                                ->iconColor('success')
                                ->body('La requisicon Nro. ${requisicion->codigo} ha sido creada con exito')
                                ->send();
                        }
                        // return redirect()->route('requisicions.show', $requisicion->id);
                    } catch (\Throwable $th) {
                        LogController::log(Auth::user()->id, 'excepcion-RequisicionController(crearRequisicion)', $th->getMessage(), $response = null);
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-c-x-circle')
                            ->color('danger')
                            ->iconColor('danger')
                            ->body($th->getMessage())
                            ->send();
                    }
                })

        ];
    }
}
