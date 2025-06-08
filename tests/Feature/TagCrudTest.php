<?php

use App\Livewire\TagCrud;
use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;

it('renders tag crud component', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TagCrud::class)
        ->assertStatus(200);
});

it('can create a tag', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TagCrud::class)
        ->set('name', 'Laravel')
        ->call('create')
        ->assertSee('Laravel');

    expect(Tag::where('name', 'Laravel')->exists())->toBeTrue();
});

it('validates required name field', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TagCrud::class)
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['name' => 'required']);
});

it('can edit a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'Viejo nombre']);

    Livewire::actingAs($user)
        ->test(TagCrud::class)
        ->call('edit', $tag->id)
        ->set('name', 'Nuevo nombre')
        ->call('update');

    expect($tag->fresh()->name)->toBe('Nuevo nombre');
});

it('can delete a tag', function () {
    $user = User::factory()->create();
    $tag = Tag::factory()->create(['name' => 'Eliminar']);

    Livewire::actingAs($user)
        ->test(TagCrud::class)
        ->call('delete', $tag->id);

    expect(Tag::find($tag->id))->toBeNull();
});
