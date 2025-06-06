<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public $search;
    public $categoria;
    public $min = 0;
    public $orden = 'recientes';

    protected $queryString = ['search', 'categoria', 'min', 'orden'];

    public function updating($property)
    {
        if (in_array($property, ['search', 'categoria', 'min', 'orden'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $articulos = Article::query()
            ->when($this->orden === 'titulo_asc', fn($q) => $q->orderBy('title', 'asc'))
            ->when($this->orden === 'titulo_desc', fn($q) => $q->orderBy('title', 'desc'))
            ->when($this->orden === 'recientes', fn($q) => $q->orderByDesc('created_at'))
            ->when($this->categoria, fn($q) => $q->where('category_id', $this->categoria))
            ->when($this->min, function ($q) {
                $q->has('comentarios', '>=', $this->min);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', "%{$this->search}%")
                        ->orWhere('content', 'like', "%{$this->search}%");
                });
            })
            ->with(['category', 'user', 'tags'])
            ->paginate(10);

        $categorias = Category::all();

        return view('livewire.post-list', compact('articulos', 'categorias'));
    }
}
