<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BadgeSKS extends Component
{
    public int $sks;
    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(int $sks)
    {
        $this->sks = $sks;
        $this->color = $sks < 3 ? 'danger' : 'success';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.badge-sks');
    }
}
