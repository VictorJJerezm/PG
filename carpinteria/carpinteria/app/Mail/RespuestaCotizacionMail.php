<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RespuestaCotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cotizacion;
    public $mensaje;

    /**
     * Create a new message instance.
     */
    public function __construct(Cotizacion $cotizacion, $mensaje)
    {
        $this->cotizacion = $cotizacion;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Respuesta a tu Cotización')
                    ->view('emails.respuestaCotizacion')
                    ->with([
                        'cotizacion' => $this->cotizacion,
                        'mensaje' => $this->mensaje
                    ]);
    }
}
