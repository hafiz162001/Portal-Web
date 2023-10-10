<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Action extends Component
{
    public $route;
    public $object;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $object)
    {
        $this->route = $route;
        $this->object = $object;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.action');
    }
}
