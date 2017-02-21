<?php

class MetroProgressBar extends MetroComponent
{
    function __construct($id, $progressBarInitialValue, $enum_MetroColour = MetroColour::cyan, $enum_MetroProgressBarSize = MetroProgressBarSize::large)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'progress ' . $enum_MetroProgressBarSize, 'data-role' => 'progress', 'data-value' => $progressBarInitialValue,
        'data-color' => 'bg-' . $enum_MetroColour, 'id' => $id]);
    }
}