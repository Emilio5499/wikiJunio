<?php

use App\Mail\ColaboradorNotificacion;
use App\Models\Article;
use App\Models\Comentario;
use App\Models\User;
use Carbon\Carbon;
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

it('elimina comentarios vacíos antiguos', function () {
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
