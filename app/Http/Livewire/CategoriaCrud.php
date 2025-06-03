<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoriaCrud extends Component
{
    public $name;
    public $categoryIdBeingEdited = null;

    public function render()
    {
        return view('livewire.category-crud', [
            'categories' => Category::latest()->get()
        ]);
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $this->name,
        ]);

        $this->reset('name');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryIdBeingEdited = $category->id;
        $this->name = $category->name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($this->categoryIdBeingEdited);
        $category->update([
            'name' => $this->name,
        ]);

        $this->reset(['name', 'categoryIdBeingEdited']);
    }

    public function delete($id)
    {
        Category::destroy($id);
    }

    public function mount()
    {
        if (!auth()->user()->can('manage categories')) {
            abort(403);
        }
    }

}
