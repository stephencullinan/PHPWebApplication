<?php

class MetroWizard extends MetroComponent
{
    function __construct()
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'wizard2', 'data-role' => 'wizard2']);
    }
    function addStep(MetroComponent $control)
    {
        $this->addElement('div', '', [0], ['class' => 'step']);
        $currentStep = $this->getNumberOfElements([0]);
        $this->addElement('div', '', [0, $currentStep], ['class' => "step-content"]);
        if($control != null)
            $this->addControl($control, [0, $currentStep, 0]);
    }
}