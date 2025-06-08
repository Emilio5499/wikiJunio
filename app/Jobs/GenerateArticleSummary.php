<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateArticleSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function handle(): void
    {
        $summary = substr(strip_tags($this->article->content), 0, 100);
        Log::info("Resumen generado: {$summary}");
    }
}
