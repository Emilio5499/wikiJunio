<?php
namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;

class TagCrud extends Component
{
    public $name;
    public $editingId = null;

    public function create()
    {
        $this->validate(['name' => 'required|string|max:255']);

        Tag::create(['name' => $this->name]);

        session()->flash('success', 'Tag creado.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->editingId = $tag->id;
        $this->name = $tag->name;
    }

    public function update()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $tag = Tag::findOrFail($this->editingId);
        $tag->update(['name' => $this->name]);

        session()->flash('success', 'Tag actualizado.');
        $this->resetForm();
    }

    public function delete($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        session()->flash('success', 'Tag eliminado.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->editingId = null;
    }

    public function render()
    {
        return view('livewire.tag-crud', [
            'tags' => Tag::latest()->get()
        ]);
    }
}

