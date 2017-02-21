<?php

class MetroAppBar extends MetroComponent
{
    function __construct($id)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'app-bar', 'data-role' => 'appbar', 'id' => $id]);
        $this->addElement('ul', '', [0], ['class' => 'app-bar-menu']);
    }
    function addMenuItem($title, $enum_MetroIcon, $onClickEvent = '')
    {
        $this->addElement('li', '', [0, 0], ['onclick' => $onClickEvent]);
        $positionOfCurrentMenuItem = $this->getNumberOfElements([0, 0]);
        $this->addElement('a', $title, [0, 0, $positionOfCurrentMenuItem]);
        $this->addElement('span', '', [0, 0, $positionOfCurrentMenuItem, 0], ['class' => 'icon mif-' . $enum_MetroIcon]);
    }
}