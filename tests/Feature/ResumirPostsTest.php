<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use App\Jobs\GenerateArticleSummary;
use App\Models\Article;

it('dispatch job for every post',function () {
    Bus::fake();

    Article::factory()->count(3)->create();

    $this->artisan('app:resumir-articulos')
        ->assertExitCode(0);

    Bus::assertDispatched(GenerateArticleSummary::class, 3);
});

it('shows message when using the command', function () {
    $output = Artisan::call('app:resumir-articulos');

    Artisan::assertSuccessful();
    expect(Artisan::output())
        ->toContain('Haciendo resumenes')
        ->toContain('Resumenes enviados');
});
