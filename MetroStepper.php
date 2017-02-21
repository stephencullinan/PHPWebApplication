<?php

class MetroStepper extends MetroComponent
{
    function __construct($id, $numberOfSteps, $initialStep = 1, $enum_MetroStepperType = MetroStepperType::default, $clickable = true)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['id' => $id, 'class' => 'stepper', 'data-role' => 'stepper', 'data-steps' => $numberOfSteps, 'data-start' => $initialStep,
        'data-type' => $enum_MetroStepperType, 'data-clickable' => $clickable]);
    }
}