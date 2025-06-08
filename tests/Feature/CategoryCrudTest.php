<?php

use App\Livewire\CategoryCrud;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the component correctly', function () {
    Livewire::actingAs($this->user)
        ->test(CategoryCrud::class)
        ->assertStatus(200);
});

it('can create a new category', function () {
    Livewire::actingAs($this->user)
        ->test(CategoryCrud::class)
        ->set('name', 'Nueva categoría')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('Categoría creada.');

    expect(Category::where('name', 'Nueva categoría')->exists())->toBeTrue();
});

it('can update an existing category', function () {
    $category = Category::factory()->create(['name' => 'Antigua']);

    Livewire::actingAs($this->user)
        ->test(CategoryCrud::class)
        ->call('edit', $category->id)
        ->set('name', 'Actualizada')
        ->call('save')
        ->assertSee('Categoría actualizada.');

    expect($category->fresh()->name)->toBe('Actualizada');
});

it('validates name field is required', function () {
    Livewire::actingAs($this->user)
        ->test(CategoryCrud::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

it('can delete a category', function () {
    $category = Category::factory()->create();

    Livewire::actingAs($this->user)
        ->test(CategoryCrud::class)
        ->call('delete', $category->id)
        ->assertSee('Categoría eliminada.');

    expect(Category::find($category->id))->toBeNull();
});
