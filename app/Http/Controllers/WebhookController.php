<?php

namespace App\Http\Controllers;

use App\Models\TasaBcv;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita ($name) {
        $res = TasaBcv::where('fecha', date('d-m-Y'))->first()->update([
            'tasa' => $name
        ]);

        if($res) {
            return response()->json(['message' => 'Tasa actualizada correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar la tasa'], 400);
                }
    }
    //
}