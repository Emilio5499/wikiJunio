<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public string $name;
    public string $id;
    public string $label;

    public function __construct(string $name, string $label = '', string $id = '')
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst(str_replace('_', ' ', $name));
        $this->id = $id ?: $name;
    }

    public function render()
    {
        return view('components.select');
    }
}
