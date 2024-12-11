<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita (Request $request) {
        return response()->json(['success' => true]);
    }
    //
}