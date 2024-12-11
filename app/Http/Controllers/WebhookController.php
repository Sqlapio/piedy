<?php

namespace App\Http\Controllers;

use App\Models\TasaBcv;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita ($name) {
        TasaBcv::where('fecha', date('d-m-Y'))->first()->update([
            'tasa' => $name
        ]);
        return response()->json(['success' => true]);
    }
    //
}