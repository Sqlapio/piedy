<?php

namespace App\Http\Controllers;

use App\Models\CarProducto;
use App\Models\Comision;
use App\Models\InventarioSucursal;
use App\Models\Producto;
use App\Models\VentaProducto;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VentaProductoController extends Controller
{
    static function facturarProducto_usd($metodoUsd, $montoUsd, $cliente_id, $empleado_id)
    {
        // dd($metodoUsd, $montoUsd, $cliente_id, $empleado_id);
        try {

            $codigoAsignacion = 'Pca-'.random_int(11111111, 99999999);

            $total_compra = CarProducto::sum('total_compra_usd');

            if($montoUsd == $total_compra){

                /**
                 * Comisiones de venta
                 */
                if($empleado_id == null)
                {
                    //La venta fue asignada al gerente
                    //Comision Gerente 15%
                    $porComGte = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'gerente')
                        ->where('accion', 'directa')
                        ->where('status', '1')->first()
                        ->porcentaje;

                }else{
                    //La venta fue asignada a un quiropedista o una manicurista
                    //Se calculan ambas comisiones tanto para el gerente como para el empleado
                    //Comision Empleado 10%
                    $porComEmp = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'empleado')
                        ->where('accion', 'directa')
                        ->where('status', '1')->first()
                        ->porcentaje;

                    //Comision Empleado 5%
                    $porComGte = Comision::where('aplicacion', 'producto')
                        ->where('beneficiario', 'gerente')
                        ->where('accion', 'indirecta')
                        ->where('status', '1')->first()
                        ->porcentaje;

                }

                $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

                foreach($productos as $item)
                {

                    $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                    $venta_producto = new VentaProducto();
                    $venta_producto->cod_asignacion     = $codigoAsignacion;
                    $venta_producto->gerente_id         = Auth::user()->id;
                    $venta_producto->producto_id        = $producto->id;
                    $venta_producto->costo_producto     = $producto->precio_venta;
                    $venta_producto->metodo_pago        = 'USD';

                    $venta_producto->metodoUsd          = $metodoUsd;
                    $venta_producto->montoUsd           = $montoUsd;

                    $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                    $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                    $venta_producto->fecha_venta        = now()->format('d-m-Y');
                    $venta_producto->cantidad           = $item->cantidad;
                    $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                    $venta_producto->responsable        = Auth::user()->name;
                    $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                    $venta_producto->cliente_id         = $cliente_id;
                    $venta_producto->empleado_id        = ($empleado_id == null) ? Auth::user()->id : $empleado_id;
                    $venta_producto->save();

                    //Descuento la cantidad vendida de la exitencia del producto por sucursal
                    $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                    $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                    $productoSucursal->save();

                    if($productoSucursal->cantidad == 0)
                    {
                        $productoSucursal->accepted_at = null;
                        $productoSucursal->save();
                    }

                    //Cargamos el movimiento de inventario en su tabla
                    MovimientoInventarioController::registrar_movimiento(
                        $producto->id,
                        $item->cantidad,
                        $venta_producto->sucursal_id,
                        $codigoAsignacion,
                        'Venta',
                    );
                }

                return true;

            }else{

                return false;
            }

        } catch (\Throwable $th) {
            dd($th);
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function facturarProducto_bsd($metodoBsd, $montoBsd, $referenciaBsd, $nroTarjeta, $cliente_id, $empleado_id = null)
    {
        try {

            $codigoAsignacion = 'Pca-'.random_int(11111111, 99999999);

            $total_compra = CarProducto::sum('total_compra_bsd');

            /**
             * Comisiones de venta
             */
            if($empleado_id == null)
            {
                //La venta fue asignada al gerente
                //Comision Gerente 15%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

            }else{
                //La venta fue asignada a un quiropedista o una manicurista
                //Se calculan ambas comisiones tanto para el gerente como para el empleado
                //Comision Empleado 10%
                $porComEmp = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'empleado')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

                //Comision Empleado 5%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'indirecta')
                    ->where('status', '1')->first()
                    ->porcentaje;

            }

            $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

            foreach($productos as $item)
            {
                $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                $venta_producto = new VentaProducto();
                $venta_producto->cod_asignacion     = $codigoAsignacion;
                $venta_producto->gerente_id         = Auth::user()->id;
                $venta_producto->producto_id        = $producto->id;
                $venta_producto->costo_producto     = $producto->precio_venta;
                $venta_producto->metodo_pago        = 'BSD';

                $venta_producto->metodoBsd          = $metodoBsd;
                $venta_producto->montoBsd           = Str::replace(',', '.', (Str::replace('.', '', $montoBsd)));

                $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                $venta_producto->fecha_venta        = now()->format('d-m-Y');
                $venta_producto->cantidad           = $item->cantidad;
                $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;
                $venta_producto->referenciaBsd      = ($referenciaBsd == null) ? 'N/a' : $referenciaBsd ;
                $venta_producto->nroTarjeta         = ($nroTarjeta == null) ? 'N/a' : $nroTarjeta;
                $venta_producto->responsable        = Auth::user()->name;
                $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                $venta_producto->cliente_id         = $cliente_id;
                $venta_producto->empleado_id        = ($empleado_id == null) ? Auth::user()->id : $empleado_id;
                $venta_producto->save();

                //Descuento la cantidad vendida de la exitencia del producto por sucursal
                $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                $productoSucursal->save();

                if($productoSucursal->cantidad == 0)
                {
                    $productoSucursal->accepted_at = null;
                    $productoSucursal->save();
                }

                //Cargamos el movimiento de inventario en su tabla
                MovimientoInventarioController::registrar_movimiento(
                    $producto->id,
                    $item->cantidad,
                    $venta_producto->sucursal_id,
                    $codigoAsignacion,
                    'Venta',
                );
            }

            if($venta_producto->save()){
                return true;
            }else{
                return false;
            }

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    static function facturarProducto_multiple($montoUsd, $montoBsd, $metodoUsd, $metodoBsd, $referenciaUsd, $referenciaBsd, $nroTarjeta, $cliente_id, $empleado_id = null)
    {
        try {

            $codigoAsignacion = 'Pca-'.random_int(11111111, 99999999);

            /**Validacion para no permitir los numero de referencias duplicados */
            $exite_referencia = VentaProducto::where('referenciaUsd', $referenciaUsd)->orWhere('referenciaBsd', $referenciaBsd)->get();
            if(count($exite_referencia) > 0){
                throw new Exception("Numero de referencia duplicado. Por favor vuelva a intentarlo", 401);
            }

            $total_compra_usd = CarProducto::sum('total_compra_usd');
            // $total_compra_bsd = CarProducto::sum('total_compra_bsd')

            /**
             * Comisiones de venta
             */
            if($empleado_id == null)
            {
                //La venta fue asignada al gerente
                //Comision Gerente 15%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

            }else{
                //La venta fue asignada a un quiropedista o una manicurista
                //Se calculan ambas comisiones tanto para el gerente como para el empleado
                //Comision Empleado 10%
                $porComEmp = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'empleado')
                    ->where('accion', 'directa')
                    ->where('status', '1')->first()
                    ->porcentaje;

                //Comision Empleado 5%
                $porComGte = Comision::where('aplicacion', 'producto')
                    ->where('beneficiario', 'gerente')
                    ->where('accion', 'indirecta')
                    ->where('status', '1')->first()
                    ->porcentaje;

            }

            $productos = CarProducto::where('sucursal_id', Auth::user()->sucursal_id)->where('status', 1)->get();

            foreach($productos as $item)
            {

                $producto = Producto::where('cod_producto', $item->cod_prod)->first();

                $venta_producto = new VentaProducto();
                $venta_producto->cod_asignacion     = $codigoAsignacion;
                $venta_producto->gerente_id         = Auth::user()->id;
                $venta_producto->producto_id        = $producto->id;
                $venta_producto->costo_producto     = $producto->precio_venta;
                $venta_producto->metodo_pago        = 'multiple';

                $venta_producto->metodoUsd          = $metodoUsd;
                $venta_producto->metodoBsd          = $metodoBsd;

                $venta_producto->montoUsd           = $montoUsd;
                $venta_producto->montoBsd           = Str::replace(',', '.', (Str::replace('.', '', $montoBsd)));

                $venta_producto->comision_gerente   = ($porComGte * $producto->precio_venta) / 100;
                $venta_producto->comision_empleado  = ($empleado_id == null) ? 0.00 : ($porComEmp * $producto->precio_venta) / 100;

                $venta_producto->fecha_venta        = now()->format('d-m-Y');
                $venta_producto->cantidad           = $item->cantidad;
                $venta_producto->total_venta        = $producto->precio_venta * $item->cantidad;

                $venta_producto->referenciaUsd      = ($referenciaUsd == null) ? 'N/A' : $referenciaUsd;
                $venta_producto->referenciaBsd      = ($referenciaBsd == null) ? 'N/A' : $referenciaBsd;

                $venta_producto->nroTarjeta         = ($nroTarjeta == null) ? 'N/A' : $nroTarjeta;
                $venta_producto->responsable        = Auth::user()->name;
                $venta_producto->sucursal_id        = Auth::user()->sucursal->id;

                $venta_producto->cliente_id         = $cliente_id;
                $venta_producto->empleado_id        = ($empleado_id == null) ? Auth::user()->id : $empleado_id;
                $venta_producto->save();

                //Descuento la cantidad vendida de la exitencia del producto por sucursal
                $productoSucursal = InventarioSucursal::where('producto_id', $producto->id)->where('sucursal_id', Auth::user()->sucursal->id)->first();
                $productoSucursal->cantidad = $productoSucursal->cantidad - $item->cantidad;
                $productoSucursal->save();

                if($productoSucursal->cantidad == 0)
                {
                    $productoSucursal->accepted_at = null;
                    $productoSucursal->save();
                }

                //Cargamos el movimiento de inventario en su tabla
                MovimientoInventarioController::registrar_movimiento(
                    $producto->id,
                    $item->cantidad,
                    $venta_producto->sucursal_id,
                    $codigoAsignacion,
                    'Venta',
                );
            }

            if($venta_producto->save()){
                return true;
            }else{
                return false;
            }

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

}
