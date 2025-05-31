<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comentario;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleAndCommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios');
            return;
        }

        Article::factory(10)->create()->each(function ($article) use ($users) {
            $article->user_id = $users->random()->id;
            $article->save();

            Comentario::factory(rand(1, 5))->create([
                'article_id' => $article->id,
                'user_id' => $users->random()->id,
            ]);
        });
    }
}
