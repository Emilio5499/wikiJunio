<?php

namespace App\Listeners;

use App\Events\ColaboradorNuevo;
use App\Mail\mailAColaborador;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class mailAColaboradorListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ColaboradorNuevo $event)
    {
        Mail::to($event->user->email)->send(
            new mailAColaborador($event->article, $event->user)
        );
    }
}
