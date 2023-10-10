<?php

namespace App\View\Components\forms;

use Illuminate\View\Component;

class DateRange extends Component
{
    public $label;
    public $name;
    public $defaultValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $defaultValue)
    {
        $this->label = $label;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.date-range');
    }
}
