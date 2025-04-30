<?php

namespace App\Listeners;

use App\Events\ArticuloCreado;
use App\Jobs\GenerateArticleSummary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class logArticuloCreado
{
    public function handle(ArticuloCreado $event)
    {
        Log::info('Artículo creado: ' . $event->article->title);
        GenerateArticleSummary::dispatch($event->article);

    }
}
