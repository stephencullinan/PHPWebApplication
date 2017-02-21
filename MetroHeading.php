<?php

class MetroHeading extends MetroComponent
{
    function __construct($headingText, $subHeadingText = '', $enum_MetroHeadingSize = '1')
    {
        parent::__construct();
        $this->addElement('h' . $enum_MetroHeadingSize, $headingText, [], ['class' => 'align-center']);
        if(strlen($subHeadingText) > 0)
            $this->addElement('small', $subHeadingText, [0]);
    }
}