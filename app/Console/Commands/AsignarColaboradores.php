<?php

namespace App\Console\Commands;

use App\Events\ColaboradorNuevo;
use App\Models\Article;
use App\Models\User;
use Illuminate\Console\Command;

class AsignarColaboradores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:asignar-colaboradores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'simula el asignar colaboradores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::inRandomOrder()->first();
        $article = Article::inRandomOrder()->first();

        if (!$user || !$article) {
            $this->error("No hay usuarios o posts.");
            return;
        }

        if (!$article->collaborators->contains($user)) {
            $article->collaborators()->attach($user->id, [
                'role_in_article' => 'colaborador',
                'joined_at' => now(),
            ]);

            event(new ColaboradorNuevo($article, $user));

            $this->info("{$user->email} asignado a '{$article->title}'.");
        } else {
            $this->warn("usuario ya asignado.");
        }
    }
}
