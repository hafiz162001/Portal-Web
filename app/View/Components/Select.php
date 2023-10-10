<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    public $globalAttribute;
    public $label;
    public $isStack;
    public $customAttribute;
    public $select2Class;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($globalAttribute, $label, $isStack=false ,$customAttribute="", $select2Class="select-2")
    {
        $this->globalAttribute = $globalAttribute;
        $this->label = $label;
        $this->isStack = $isStack;
        $this->customAttribute = $customAttribute;
        $this->select2Class = $select2Class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.select');
    }
}
