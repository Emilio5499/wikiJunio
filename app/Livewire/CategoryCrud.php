<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryCrud extends Component
{
    public $name = '';
    public $editingId = null;

    /**
     * Guarda o actualiza una categoría.
     */
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name]
        );

        session()->flash('success', $this->editingId ? 'Categoría actualizada.' : 'Categoría creada.');

        $this->reset(['name', 'editingId']);
    }

    /**
     * Prepara el formulario para editar una categoría existente.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->name = $category->name;
        $this->editingId = $category->id;
    }

    /**
     * Elimina una categoría.
     */
    public function delete($id)
    {
        Category::findOrFail($id)->delete();

        session()->flash('success', 'Categoría eliminada.');
    }
    public function render()
    {
        return view('livewire.category-crud', [
            'categories' => Category::latest()->get(),
        ]);
    }
}
