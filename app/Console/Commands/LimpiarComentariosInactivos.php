<?php

namespace App\Console\Commands;

use App\Models\Comentario;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LimpiarComentariosInactivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comentarios:limpiar-inactivos
                            {--dias=60 : Se borran comentarios viejos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra comentarios viejos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limite = Carbon::now()->subDays($this->option('dias'));

        $comentarios = Comentario::where(function ($query) {
            $query->whereNull('content')
                ->orWhere('content', '');
        })
            ->where('created_at', '<=', $limite)
            ->get();

        $total = $comentarios->count();

        if ($total === 0) {
            $this->info('No hay comentarios que eliminar.');
            return Command::SUCCESS;
        }

        Comentario::whereIn('id', $comentarios->pluck('id'))->delete();

        $this->info("$total Se eliminaron $total comentarios.");

        return Command::SUCCESS;
    }
}
