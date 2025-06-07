<?php

namespace App\Listeners;

use App\Events\ColaboradorNuevo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class logColaboradorNuevo
{
    public function handle(ColaboradorNuevo $event)
    {
        Log::info("Usuario asignado como colaborador añ articulo");
    }
}
