<?php

namespace App\Console\Commands;

use App\Mail\ColaboradorNotificacion;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotificarColaboradores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuarios:notificar-colaboradores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manda un correo a los colaboradores de los posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $articles = Article::with('collaborators', 'user')->get();
        $total = 0;

        foreach ($articles as $article) {
            foreach ($article->collaborators as $colaborador) {
                Mail::to($colaborador->email)->queue(new ColaboradorNotificacion($article));
                $this->info("Correo enviado a {$colaborador->email}");
                $total++;
            }
        }

        $this->info("$total correos enviados");

        return Command::SUCCESS;
    }
}
