<?php

namespace App\Livewire;

use App\Models\Cliente;
use Filament\Notifications\Notification;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class ModalEditarCliente extends ModalComponent
{
    use WithPagination;

    use Actions;

    public Cliente $cliente;

    #[Validate('required', message: 'Nombre requerido')]
    public $nombre;

    #[Validate('required', message: 'Apellido requerido')]
    public $apellido;

    #[Validate('required', message: 'El número de cedula es requido')]
    public $cedula;

    public $email;

    public $telefono;

    public function mount(Cliente $cliente){
        $this->cliente = $cliente;
        $this->email = $this->cliente->email;
        $this->nombre = $this->cliente->nombre;
        $this->apellido = $this->cliente->apellido;
        $this->cedula = $this->cliente->cedula;
        $this->telefono = $this->cliente->telefono;

    }

    public function actualizar_cliente(){

        $this->validate();

        try {

            $this->cliente->update([
                "nombre" => $this->nombre,
                "apellido" => $this->apellido,
                "cedula" => $this->cedula,
                "telefono" => $this->telefono,
                "email" => $this->email,
            ]);

            Notification::make()
            ->title('NOTIFICACION')
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->body('La información del cliente fue actualizada con exito')
            ->send();

            $this->reset();

            $this->closeModal();

            redirect(route('clientes'));

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACION')
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->body($th->getMessage())
            ->send();
        }
    }

    public function render()
    {

        return view('livewire.modal-editar-cliente');
    }
}
