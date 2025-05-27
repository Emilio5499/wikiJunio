<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public string $label;
    public string $name;
    public ?string $id;
    public ?string $value;
    public ?int $rows;

    public function __construct($label, $name, $id = null, $value = null, $rows = 4)
    {
        $this->label = $label;
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select');
    }
}
