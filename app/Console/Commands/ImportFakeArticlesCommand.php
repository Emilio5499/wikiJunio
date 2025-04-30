<?php

namespace App\Console\Commands;

use App\Jobs\ImportFakeArticles;
use Illuminate\Console\Command;

class ImportFakeArticlesCommand extends Command
{
    protected $signature = 'import:fake-articles {count=10}';

    protected $description = 'posts prueba';

    public function handle()
    {
        $count = (int) $this->argument('count');

        ImportFakeArticles::dispatch($count);

        $this->info("Creados $count posts.");
    }
}
