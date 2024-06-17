<?php

namespace App\Livewire;

use Livewire\Component;

class Nomina extends Component
{
    public function redir($value)
    {
        if($value == 1)
        {
            {
                $this->redirect('/n/q');
            }

        }

        if($value == 2)
        {
            {
                $this->redirect('/n/m');
            }

        }

        if($value == 3)
        {
            {
                $this->redirect('/n/e');
            }

        }
    }

    public function render()
    {
        return view('livewire.nomina');
    }
}
