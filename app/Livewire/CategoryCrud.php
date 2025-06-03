<?php

// app/Livewire/TagCrud.php
namespace App\Livewire;

use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;

class CategoryCrud extends Component
{
    public $name, $editingId;

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        Category::updateOrCreate(['id' => $this->editingId], ['name' => $this->name]);

        $this->reset(['name', 'editingId']);
    }

    public function edit($id)
    {
        $Category = Category::findOrFail($id);
        $this->name = $Category->name;
        $this->editingId = $Category->id;
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.tag-crud', ['category' => Tag::all()]);
    }
}
