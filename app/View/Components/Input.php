<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public string $name;
    public string $id;
    public string $label;
    public ?string $value;
    public string $type;

    public function __construct(string $name, string $label = '', string $id = '', string $value = null, string $type = 'text')
    {
        $this->name = $name;
        $this->label = $label ?: ucfirst(str_replace('_', ' ', $name));
        $this->id = $id ?: $name;
        $this->value = $value;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.input');
    }
}
