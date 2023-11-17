<?php

namespace App\Livewire;

use App\Models\TasaBcv;
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
    }

    public function render()
    {
        $tasa = TasaBcv::first();
        return view('livewire.dashboard', compact('tasa'));
    }
}
