<?php

namespace App\Livewire;

use App\Models\CierreDiario;
use App\Models\FacturaMultiple;
use App\Models\TasaBcv;
use App\Models\VentaServicio;
use Livewire\Component;
use WireUi\Traits\Actions;

class Dashboard extends Component
{
    use Actions;

    public function valida_tasa($valor)
    {
        $tasa = TasaBcv::first();

        if($valor == 1)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/clientes');
            }

        }

        if($valor == 2)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/cabinas');
            }
        }

        if($valor == 3)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/citas');
            }
        }

        if($valor == 4)
        {
            $this->dialog()->success(
                $title = 'NOTIFICACION !!!',
                $description = 'El equipo de desarrollo se encuentra trabajando en ECOMMERCE.'
            );
            // if($tasa->fecha != date('d-m-Y'))
            // {
            //     $this->dialog()->error(
            //         $title = 'Error !!!',
            //         $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
            //     );
            // }else{
            //     $this->redirect('/productos');
            // }
        }

        if($valor == 5)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/servicios');
            }
        }

        if($valor == 6)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/venta');
            }
        }

        if($valor == 7)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/gastos');
            }
        }

        if($valor == 8)
        {

            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/cierre/diario');
            }



            // $this->dialog()->success(
            //         $title = 'NOTIFICACION !!!',
            //         $description = 'El equipo de desarrollo se encuentra trabajando en la creación de esta funcionalidad.'
            //     );

            // $this->dialog()->confirm([

            //     'title'       => 'Notificación de sistema',
            //     'description' => 'Usted se dispone a realizar un cierre de caja. Esta acción totaliza los movimientos generados hasta la fecha y hora en curso.',
            //     'icon'        => 'warning',
            //     'accept'      => [
            //         'label'  => 'Si, realizar cierre',
            //         'method' => 'cierre_caja',
            //         'params' => 'Saved',
            //     ],
            //     'reject' => [
            //         'label'  => 'No, cancelar',
            //         'method' => 'cancel',

            //     ],

            // ]);

        }
    }

    public function cierre_caja()
    {
        /** totales en la tabla de ventas */
        $total_venta_neta  = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('total_USD');
        $total_pagos_usd   = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
        $total_pagos_bsd   = VentaServicio::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

        /** totales en facturas multiples */
        $fm_pagos_usd      = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_usd');
        $fm_pagos_bsd      = FacturaMultiple::where('fecha_venta', date('d-m-Y'))->sum('pago_bsd');

        /** totales en gastos */

        $cierre = new CierreDiario();


    }

    public function cancel()
    {
        dd('cancelar');
    }

    public function render()
    {
        $tasa = TasaBcv::first();
        return view('livewire.dashboard', compact('tasa'));
    }
}
