<?php

use App\Livewire\CategoryCrud;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

it('renders the CategoryCrud component', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CategoryCrud::class)
        ->assertStatus(200);
});

it('can create a new category', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CategoryCrud::class)
        ->set('name', 'Programación')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSee('Categoría creada.');

    expect(Category::where('name', 'Programación')->exists())->toBeTrue();
});

it('validates the name when creating', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CategoryCrud::class)
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['name' => 'required']);
});

it('can update a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['name' => 'Inicial']);

    Livewire::actingAs($user)
        ->test(CategoryCrud::class)
        ->call('edit', $category->id)
        ->set('name', 'Actualizada')
        ->call('update')
        ->assertSee('Categoría actualizada.');

    expect($category->fresh()->name)->toBe('Actualizada');
});

it('can delete a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    Livewire::actingAs($user)
        ->test(CategoryCrud::class)
        ->call('delete', $category->id)
        ->assertSee('Categoría eliminada.');

    expect(Category::find($category->id))->toBeNull();
});

