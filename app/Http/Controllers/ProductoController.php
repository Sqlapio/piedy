<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    static function get_dscripcion($id) {
        $producto = Producto::find($id);
        return $producto->descripcion.'-'.$producto->contenido_neto.''.$producto->unidad;
    }
}