<?php

use App\Events\ColaboradorNuevo;
use App\Listeners\LogColaboradorNuevo;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Log;

it('writes  in log when collaborator is assigned', function () {
    $user = User::factory()->create(['name' => 'Carlos']);
    $article = Article::factory()->create(['title' => 'Test Post']);

    $expectedMessage = "Carlos es ahora colaborador del post 'Test Post'";

    Log::shouldReceive('info')
        ->once()
        ->with($expectedMessage);

    $event = new ColaboradorNuevo($article, $user);
    $listener = new LogColaboradorNuevo();

    $listener->handle($event);
});

