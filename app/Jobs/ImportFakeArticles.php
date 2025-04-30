<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportFakeArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $count;

    public function __construct($count = 10)
    {
        $this->count = $count;
    }

    public function handle()
    {
        $user = User::first();

        for ($i = 0; $i < $this->count; $i++) {
            Article::create([
                'title' => 'Articulo #' . Str::random(5),
                'content' => 'contenido falso.',
                'user_id' => $user->id,
            ]);
        }
    }
}
