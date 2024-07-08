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
                $this->redirect('/empleados');
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
                $this->redirect('/clientes');
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
                $this->redirect('/cabinas');
            }
        }

        if($valor == 4)
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

        if($valor == 5)
        {
            // $this->dialog()->success(
            //     $title = 'NOTIFICACION !!!',
            //     $description = 'El equipo de desarrollo se encuentra trabajando en ECOMMERCE.'
            // );
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/productos');
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
                $this->redirect('/servicios');
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
                $this->redirect('/venta');
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
                $this->redirect('/gastos');
            }
        }

        if($valor == 9)
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

        }

        if($valor == 10)
        {

            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/nomina');
            }
        }

        if($valor == 11)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/reporte');
            }

        }

        if($valor == 12)
        {
            if($tasa->fecha != date('d-m-Y'))
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe actualizar la tasa del BCV para poder utilizar el sistema. Por favor haga click en el simbolo del BCV.'
                );
            }else{
                $this->redirect('/reporte/general');
            }

        }

        if($valor == 13)
        {
            $this->redirect('/l/srv');

        }
    }

    public function cierre_general()
    {
        $this->redirect('/cierre/general');
    }

    public function gift()
    {
        $this->redirect('/g/m');
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
