<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-mail-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para enviar correos masivos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $fecha_anterior =  date("d-m-Y", strtotime(date("d-m-Y") . "-1 day"));
        // $clientes = VentaServicio::where('fecha_venta', $fecha_anterior)->get();

        // foreach($clientes as $item)
        // {
        //     $cliente = Cliente::find($item->cliente_id);
        //     dump($cliente);
        //     // $view = 'emails.correo_masivo';
        //     // $mailData = [
        //     //     'cliente' => $cliente->nombre.' '.$cliente->apellido
        //     // ];

        //     // Mail::to($cliente->email)->send(new NotificacionesEmail($mailData, $view));

        // }
        $this->info('tarea 1');

    }
}
