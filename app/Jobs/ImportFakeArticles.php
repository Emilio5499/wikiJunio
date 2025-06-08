<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Faker\Factory as Faker;

class ImportFakeArticles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function handle(): void
    {
        $faker = Faker::create();

        $user = User::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        if (!$user || !$category) {
            logger()->warning('No hay usuario o categoría para artículos falsos.');
            return;
        }

        for ($i = 0; $i < $this->count; $i++) {
            Article::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'user_id' => $user->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
