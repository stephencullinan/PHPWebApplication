<?php

class MetroAccordion extends MetroComponent
{
    private $id;
    function __construct($id, $largeSize = true, $visible = true)
    {
        parent::__construct();
        $this->id = $id;
        $accordionAttributes = 'accordion ';
        if($largeSize == true)
            $accordionAttributes .= ' large-heading';
        if($visible == true)
            $this->addElement('div', '', [], ['id' => $id, 'data-role' => 'accordion', 'class' => $accordionAttributes]);
        else
            $this->addElement('div', '', [], ['id' => $id, 'data-role' => 'accordion', 'class' => $accordionAttributes, 'style' => 'display:none;']);
    }
    public function addItem($itemTitle, $itemContent, $enum_MetroIcon, $activeItem = true, $disabledItem = false)
    {
        $accordionItemAttributes = 'frame ';
        if($activeItem == true)
            $accordionItemAttributes .= 'active ';
        if($disabledItem == true)
            $accordionItemAttributes .= 'disabled ';
        $this->addElement('div', '', [0], ['class' => $accordionItemAttributes]);
        $accordionItemNumber = $this->getNumberOfElements([0]);
        $this->addElement('div', $itemTitle, [0, $accordionItemNumber], ['class' => "heading"]);
        $this->addElement('span', '', [0, $accordionItemNumber, 0], ['class' => 'mif-' . $enum_MetroIcon . ' icon']);
        $this->addElement('div', $itemContent, [0, $accordionItemNumber], ['class' => 'content']);
    }
    public function addItemAsControl($itemTitle, MetroComponent $itemControl, $enum_MetroIcon, $activeItem = true, $disabledItem = false)
    {
        $accordionItemAttributes = 'frame ';
        if($activeItem == true)
            $accordionItemAttributes .= 'active ';
        if($disabledItem == true)
            $accordionItemAttributes .= 'disabled ';
        $this->addElement('div', '', [0], ['class' => $accordionItemAttributes]);
        $accordionItemNumber = $this->getNumberOfElements([0]);
        $this->addElement('div', $itemTitle, [0, $accordionItemNumber], ['class' => 'heading', 'id' => $this->id . '_' . $accordionItemNumber]);
        $this->addElement('span', '', [0, $accordionItemNumber, 0], ['class' => 'mif-' . $enum_MetroIcon . ' icon']);
        $this->addElement('div', '', [0, $accordionItemNumber], ['class' => 'content']);
        if($itemControl != null)
            $this->addControl($itemControl, [0, $accordionItemNumber, 1]);
    }
}