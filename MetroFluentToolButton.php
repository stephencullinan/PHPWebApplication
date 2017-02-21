<?php

class MetroFluentToolButton extends MetroComponent
{
    function __construct($id, $onClickEvent, $imageUrl = '')
    {
        parent::__construct();
        $this->addElement('button', '', [], ['class' => 'fluent-tool-button', 'id' => $id, 'onclick' => $onClickEvent]);
        $this->addElement('img', '', [0], ['src' => $imageUrl]);
    }
}