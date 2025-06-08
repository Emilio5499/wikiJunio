<?php

use Illuminate\Support\Facades\Artisan;
use App\Jobs\GenerateArticleSummary;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\BufferedOutput;

it('shows message when using the command', function () {
    $status = Artisan::call('app:resumir-articulos');

    expect($status)->toBe(0);

});

it('runs command successfully', function () {
    Article::factory()->count(2)->create();

    $output = new BufferedOutput();

    $exitCode = Artisan::call('app:resumir-articulos', [], $output);

    expect($exitCode)->toBe(0);
    $result = $output->fetch();
    expect($result)->toContain('Haciendo resumenes')
        ->toContain('Resumenes enviados');
});


it('command handles no articles', function () {
    Article::query()->delete();

    $output = new BufferedOutput();

    $exitCode = Artisan::call('app:resumir-articulos', [], $output);

    expect($exitCode)->toBe(0);
    $result = $output->fetch();
    expect($result)->toContain('Haciendo resumenes')
        ->toContain('Resumenes enviados');
});

it('trims content to 100 characters in summary', function () {
    Log::shouldReceive('info')
        ->once()
        ->withArgs(fn ($msg) => strlen($msg) <= 120 && str_contains($msg, 'Resumen generado:'));

    $article = Article::factory()->create([
        'content' => str_repeat('Texto largo ', 20),
    ]);

    (new GenerateArticleSummary($article))->handle();
});

