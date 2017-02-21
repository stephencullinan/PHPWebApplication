<?php

class MetroFluentButton extends MetroComponent
{
    function __construct($id, $onClickEvent, $buttonText, $enum_MetroIcon)
    {
        parent::__construct();
        $this->addElement('button', $buttonText, [], ['class' => 'fluent-button', 'id' => $id, 'onclick' => $onClickEvent]);
        $this->addElement('span', '', [0], ['class' => 'mif-' . $enum_MetroIcon]);
    }
}