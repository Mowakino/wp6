<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Carousel extends Component
{
    public $recipes;

    public function __construct($recipes)
    {
        $this->recipes = $recipes;
    }

    public function render()
    {
        return view('components.carousel');
    }
}
