<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comentario;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleAndCommentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'user@wiki.com'
        ], [
            'name' => 'Usuario',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $categories = Category::factory()->count(3)->create();

        $tags = Tag::factory()->count(5)->create();

        $usageTypes = ['post nuevo', 'spoiler', 'debate'];

        Article::factory()->count(5)->create([
            'user_id' => $user->id,
        ])->each(function ($article) use ($categories, $tags, $usageTypes, $user) {
            $article->category_id = $categories->random()->id;
            $article->save();

            $syncData = [];
            $selectedTags = $tags->random(rand(1, 3));
            foreach ($selectedTags as $tag) {
                $syncData[$tag->id] = ['usage_type' => collect($usageTypes)->random()];
            }
            $article->tags()->sync($syncData);

            Comentario::factory()->count(3)->create([
                'article_id' => $article->id,
                'user_id' => $user->id,
            ]);
        });
    }
}
