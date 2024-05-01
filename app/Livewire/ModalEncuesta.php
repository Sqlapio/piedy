<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\FichaMedica;
use Livewire\Component;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class ModalEncuesta extends ModalComponent
{
    use WithPagination;

    use Actions;

    public Cliente $cliente;

    public $p1;
    public $p2;
    public $p3;
    public $p4;
    public $p5;

    public $comentario_adicional;

    public function mount(Cliente $cliente){
        $this->cliente = $cliente;
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function store(){

        try {

            /** Logica para cargar los datos de la encuesta despues de guardar el cliente nuevo */
            
            $ficha_medica = new FichaMedica();
            $ficha_medica->p1 = $this->p1;
            $ficha_medica->p2 = $this->p2;
            $ficha_medica->p3 = $this->p3;
            $ficha_medica->p4 = $this->p4;
            $ficha_medica->p5 = $this->p5;
            $ficha_medica->comentario_adicional = $this->comentario_adicional;
            $ficha_medica->cliente_id = $this->cliente->id;
            $ficha_medica->save();

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function render()
    {
        return view('livewire.modal-encuesta');
    }
}
