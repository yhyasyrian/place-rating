<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $photo,
        public ?string $name,
        public ?string $link,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card',[
            'photo' => $this->photo,
            'name' => $this->name,
            'link' => $this->link,
        ]);
    }
}
