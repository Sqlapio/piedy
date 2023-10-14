<?php

namespace App\Livewire;

use Livewire\Component;

class Cabinas extends Component
{
    public $count = 0;
    public function increment() {
        $this->count++;
        if($this->count == 60)
        {
            $this->reset('count');
        }
    }
    public function render()
    {
        return view('livewire.cabinas');
    }
}
