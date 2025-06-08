<?php

namespace App\Listeners;

use App\Events\ColaboradorNuevo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogColaboradorNuevo
{
    public function handle(ColaboradorNuevo $event)
    {
        Log::info("{$event->user->name} es ahora colaborador del post '{$event->article->title}'");
    }
}
