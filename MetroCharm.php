<?php

class MetroCharm extends MetroComponent
{
    function __construct($id, $enum_MetroCharmPosition = MetroCharmPosition::top)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['data-role' => 'charm', 'id' => $id, 'data-position' => $enum_MetroCharmPosition]);
    }
    function addText($title)
    {
        $this->addElement('h1', $title, [0]);
    }
}