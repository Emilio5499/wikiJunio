<?php

namespace App\Console\Commands;

use App\Jobs\GenerateArticleSummary;
use App\Models\Article;
use Illuminate\Console\Command;

class ResumirArticulos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'app:resumir-articulos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera resumenes de los posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Haciendo resumenes");

        Article::all()->each(function ($article) {
            GenerateArticleSummary::dispatch($article);
        });

        $this->info("Resumenes enviados");
    }
}
