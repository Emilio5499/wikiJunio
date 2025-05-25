<?php

namespace App\Console\Commands;

use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportArticlesToPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articulos:exportar-pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mete todos los posts en un PDF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $articles = Article::with('user', 'category')->get();

        $pdf = Pdf::loadView('pdf.article-summary', [
            'articles' => $articles,
        ]);

        $filename = 'resumen_articulos_' . now()->format('Ymd_His') . '.pdf';

        Storage::disk('local')->put("pdfs/{$filename}", $pdf->output());

        $this->info("PDF creado en storage/app/pdfs/{$filename}");

        return Command::SUCCESS;
    }
}
