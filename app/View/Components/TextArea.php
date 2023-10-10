<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextArea extends Component
{
    public $globalAttribute;
    public $label;
    public $defaultValue;
    public $customAttribute;
    public $rows;
    public $isRequired;
    public $isTinyMce;
    public $isStack;
    public $isSummerNote;
    public $isCkEditor;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($defaultValue, $globalAttribute, $label = false, $customAttribute = "", $rows = false, $isRequired = false, $isTinyMce = false, $isStack = false, $isSummerNote = false, $isCkEditor = false)
    {
        $this->globalAttribute = $globalAttribute;
        $this->label = $label;
        $this->rows = $rows;
        $this->defaultValue = $defaultValue;
        $this->customAttribute = $customAttribute;
        $this->isRequired = $isRequired;
        $this->isTinyMce = $isTinyMce;
        $this->isStack = $isStack;
        $this->isSummerNote = $isSummerNote;
        $this->isCkEditor = $isCkEditor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.text-area');
    }
}
