<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CountDown extends Component
{
    public $start;

    public function begin()
    {
        $start_memory = Session::get('contador');

        if(isset($start_memory)){
            $this->start = $start_memory;
            $this->start = $this->start + 1;
            Session::put('contador', $this->start);

        }else{
            $this->start = $this->start + 1;
            Session::put('contador', $this->start);

        }

    }

    public function render()
    {

        return <<<'HTML'
        <div>
            <h1>Count: <span wire:poll="begin">{{ $start }}</span></h1>
        </div>
        HTML;
    }
}
