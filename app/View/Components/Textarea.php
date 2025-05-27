<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    public string $name;
    public string $id;
    public string $label;
    public ?string $value;
    public int $rows;

    public function __construct(string $name, string $label = '', string $id = '', string $value = null, int $rows = 4)
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst(str_replace('_', ' ', $name));
        $this->id = $id ?: $name;
        $this->value = $value;
        $this->rows = $rows;
    }

    public function render()
    {
        return view('components.textarea');
    }
}

