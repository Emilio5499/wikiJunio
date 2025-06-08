<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\{actingAs, post};

it('authenticated user can request email verification notification', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    actingAs($user);

    post(route('verification.send'))
        ->assertRedirect()
        ->assertSessionHas('status', 'verification-link-sent');

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('verified users are not sent a verification email', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($user);

    post(route('verification.send'))
        ->assertRedirect()
        ->assertSessionMissing('status');

    Notification::assertNothingSent();
});

it('guest users cannot request email verification', function () {
    post(route('verification.send'))->assertRedirect('/login');
});
