<?php

class MetroIconFont extends MetroComponent
{
    function __construct($enum_MetroIcon, $enum_MetroIconSize = MetroIconSize::one)
    {
        parent::__construct();
        $this->addElement('span', '', [], ['class' => 'mif-' . $enum_MetroIcon . ' ' . $enum_MetroIconSize]);
    }
}