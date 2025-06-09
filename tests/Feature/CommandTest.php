<?php

use App\Jobs\ImportFakeArticles;
use App\Mail\ColaboradorNotificacion;
use App\Models\Article;
use App\Models\Comentario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

it('generates a PDF of the posts', function () {
    Storage::fake('local');

    \App\Models\Article::factory()->create();

    $this->artisan('articulos:exportar-pdf')
        ->expectsOutputToContain('PDF creado')
        ->assertExitCode(0);

    Storage::disk('local')->assertExists('pdfs');
});

it('deletes old empty comments', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    Comentario::create([
        'user_id' => $user->id,
        'article_id' => $article->id,
        'content' => '',
        'created_at' => now()->subDays(31)->startOfDay(),
    ]);

    $this->artisan('comentarios:limpiar-inactivos --dias=30')
        ->assertExitCode(0);

    expect(Comentario::count())->toBe(1);
});

it('sends mail to post collaborators', function () {
    Mail::fake();

    $colaborador = User::factory()->create();
    $autor = User::factory()->create();
    $article = Article::factory()->create(['user_id' => $autor->id]);
    $article->collaborators()->attach($colaborador);

    $this->artisan('usuarios:notificar-colaboradores')
        ->expectsOutputToContain('Correo enviado')
        ->assertExitCode(0);

    Mail::assertQueued(ColaboradorNotificacion::class);
});

it('uses command AsignarColaboradores without error', function () {
    $this->artisan('articles:asignar-colaboradores')
        ->assertExitCode(0);
});

it('dispatch job with correct number of posts', function () {
    Bus::fake();

    Artisan::call('import:fake-articles 7');

    Bus::assertDispatched(ImportFakeArticles::class, function ($job) {
        return $job->count === 7;
    });
});

it('shows message in console', function () {
    Artisan::call('import:fake-articles 5');

    $output = Artisan::output();
    expect($output)->toContain('Creados 5 posts.');
});

it('creates 10 posts if no other instruction is given', function () {
    Bus::fake();

    Artisan::call('import:fake-articles');

    Bus::assertDispatched(ImportFakeArticles::class, function ($job) {
        return $job->count === 10;
    });
});

it('assigns a collaborator to an article if not already assigned', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create();

    $this->artisan('articles:asignar-colaboradores')
        ->assertExitCode(0);

    $this->assertDatabaseHas('article_user', [
        'article_id' => $article->id,
    ]);
});

it('shows error if there are no users or articles', function () {
    \App\Models\User::query()->delete();
    \App\Models\Article::query()->delete();

    $this->artisan('articles:asignar-colaboradores')
        ->expectsOutputToContain('No hay usuarios o posts.')
        ->assertExitCode(0);
});
