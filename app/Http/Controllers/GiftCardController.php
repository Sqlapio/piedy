<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\Membresia;
use Illuminate\Support\Facades\Auth;

class GiftCardController extends Controller
{
    public static function validaGiftCard($codigo, $cod_asignacion)
    {
        $item = Disponible::where('cod_asignacion', $cod_asignacion)
        ->where('sucursal_id', Auth::user()->sucursal_id)
        ->where('status', 'cerrado')
        ->first();

        $valida_codigo = GiftCard::where('pgc', $codigo)->first();

        if (isset($valida_codigo)) {

            if ($valida_codigo->status == '1' && $valida_codigo->cliente_id != $item->cliente_id) {
                return $array = [
                    'status' => 'error',
                    'mensaje' => 'TARJETA GIFTCARD ACTIVA!, PERO NO PERTENECE AL CLIENTE',
                ];
            }

            if ($valida_codigo->status == '2') {
                return $array = [
                    'status' => 'error',
                    'mensaje' => 'TARJETA GIFTCARD INACTIVA. FECHA DE USO: ' . $valida_codigo->updated_at . ''
                ];
            }
        } else {
            return $array = [
                'status' => 'error',
                'mensaje' => 'CODIGO NO EXISTE'
            ];
        }

        $monto_giftCard = $valida_codigo->monto;
        $venta_total = DetalleAsignacion::where('cod_asignacion', $cod_asignacion)
        ->where('sucursal_id', Auth::user()->sucursal_id)
        ->where('status', 2)
        ->sum('costo');

        $resta = $venta_total - $monto_giftCard;

        if($resta < 0){
            return $array = [
                'status' => 'error',
                'mensaje' => 'El monto de la GiftCard es mayor las monto total de venta'
            ];
        }else{
            return $array = [
                'status' => 'true',
                'valor' => $resta
            ];
        }

    }
    //
}
