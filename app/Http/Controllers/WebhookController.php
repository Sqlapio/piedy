<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function webhookAgendarCita ($name) {
        dd($name);
        // return response()->json(['success' => true]);
    }
    //
}