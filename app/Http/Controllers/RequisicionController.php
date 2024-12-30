<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use App\Models\DetalleRequisicion;

class RequisicionController extends Controller
{
    static function detalleRequisicion($codigo, $sucursal_id)
    {
        try {
            
            $detalle = Requisicion::where('codigo', $codigo)->where('sucursal_id', $sucursal_id)->with('sucursal')->first();
            $responsable = User::where('id', $detalle->user_id)->first()->name;
            return view('detalle-requisicion', compact('codigo', 'sucursal_id', 'detalle', 'responsable'));
            
        } catch (\Throwable $th) {
            // dd($th);
            LogController::log(1, 'excepcion(detalleRequisicion Link externo)', $th->getMessage(), $response = null);
        }
    }
}