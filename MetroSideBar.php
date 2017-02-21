<?php

class MetroSideBar extends MetroComponent
{
    function __construct($id, $enum_MetroSideBarType = MetroSideBarType::navy, $onClickEvent = '')
    {
        parent::__construct();
        $this->addElement('ul', '', [], ['class' => 'sidebar ' . $enum_MetroSideBarType, 'id' => $id, 'onclick' => $onClickEvent]);
    }
    function addItem($titleText, $descriptionText, $enum_MetroIcon, $onClickEvent = '')
    {
        $this->addElement('li', '', [0], ['onclick' => $onClickEvent]);
        $positionOfCurrentListItem = $this->getNumberOfElements([0]);
        $this->addElement('a', '', [0, $positionOfCurrentListItem]);
        $this->addElement('span', '', [0, $positionOfCurrentListItem, 0], ['class' => 'mif-' . $enum_MetroIcon . ' icon']);
        $this->addElement('span', $titleText, [0, $positionOfCurrentListItem, 0], ['class' => 'title']);
        $this->addElement('span', $descriptionText, [0, $positionOfCurrentListItem, 0], ['class' => 'counter']);
    }
}