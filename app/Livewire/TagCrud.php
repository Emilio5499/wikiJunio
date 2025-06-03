<?php
namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;

class TagCrud extends Component
{
    public $name, $editingId;

    public function save()
    {
        $this->validate(['name' => 'required|string|max:255']);

        Tag::updateOrCreate(['id' => $this->editingId], ['name' => $this->name]);

        $this->reset(['name', 'editingId']);
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->name = $tag->name;
        $this->editingId = $tag->id;
    }

    public function delete($id)
    {
        Tag::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.tag-crud', ['tags' => Tag::all()]);
    }
}

