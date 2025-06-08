<?php

use App\Jobs\GenerateArticleSummary;
use App\Models\Article;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Artisan;

it('shows start and end messages', function () {
    Artisan::call('app:resumir-articulos');

    $output = Artisan::output();
    expect($output)->toContain('Haciendo resumenes')
        ->toContain('Resumenes enviados');
});

it('doesnt show error if no posts exist', function () {
    Bus::fake();

    Article::query()->delete();

    Artisan::call('app:resumir-articulos');

    Bus::assertNotDispatched(GenerateArticleSummary::class);

    $output = Artisan::output();
    expect($output)->toContain('Haciendo resumenes')
        ->toContain('Resumenes enviados');
});

it('generates a summary and stores it on summary row', function () {
    $article = Article::factory()->create([
        'content' => 'Este es un contenido de prueba que debería ser resumido adecuadamente por el job.',
        'summary' => null,
    ]);

    $job = new GenerateArticleSummary($article);
    $job->handle();

    $article->refresh();

    expect($article->summary)->not()->toBeNull()
        ->and($article->summary)->toStartWith('Este es un contenido');
});

it('doesnt overwrite an already existing summary', function () {
    $article = Article::factory()->create([
        'content' => 'Contenido original',
        'summary' => 'Resumen ya generado',
    ]);

    $job = new GenerateArticleSummary($article);
    $job->handle();

    $article->refresh();

    expect($article->summary)->toBe('Resumen ya generado');
});
