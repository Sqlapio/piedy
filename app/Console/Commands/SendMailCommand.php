<?php

namespace App\Console\Commands;

use App\Mail\NotificacionesEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
        $array = [
            ['name' => 'Jhonny Martinez', 'email' => 'jhonnymartinez901@gmail.com'],
            ['name' => 'Gustavo Camacho', 'email' => 'gusta.acp@gmail.com'],
            ['name' => 'Katherine Aranguren', 'email' => 'karanguren12@gmail.com']
        ];
        // $fecha_anterior =  date("d-m-Y", strtotime(date("d-m-Y") . "-1 day"));
        // $clientes = VentaServicio::where('fecha_venta', $fecha_anterior)->get();

        foreach($array as $item)
        {
            // $cliente = Cliente::find($item->cliente_id);
            // dump($cliente);
            $view = 'emails.correo_masivo';
            $mailData = [
                'cliente' => $item['name'],
            ];

            Mail::to($item['email'])->send(new NotificacionesEmail($mailData, $view));

        }
        
        echo "Correos enviados";

    }
}
