<?php

class MetroPreLoader extends MetroComponent
{
    function __construct($enum_MetroPreLoaderType = MetroPreLoaderType::Cycle, $enum_MetroPreLoaderBackground = MetroPreLoaderBackground::color)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['data-role' => 'preloader', 'data-type' => $enum_MetroPreLoaderType, 'data-style' => $enum_MetroPreLoaderBackground]);
    }
}