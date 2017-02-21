<?php

class MetroFluentBigButton extends MetroComponent
{
    function __construct($id, $onClickEvent, $enum_MetroIcon, $buttonText)
    {
        parent::__construct();
        $this->addElement('button', $buttonText, [], ['class' => 'fluent-big-button', 'id' => $id, 'onclick' => $onClickEvent]);
        $this->addElement('span', '', [0], ['class' => 'icon mif-' . $enum_MetroIcon]);

    }
}
