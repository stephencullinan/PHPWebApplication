<?php

class MetroUpdatePanelWithNoLoadingPanel extends MetroComponent
{
    function __construct($id, MetroComponent $control = null)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['id' => $id]);
        if($control != null)
            $this->addControl($control, [0]);
    }
}