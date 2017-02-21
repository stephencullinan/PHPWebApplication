<?php

class MetroPopover extends MetroComponent
{
    function __construct($id, $text, $enum_MetroColour_Background = MetroColour::cyan, $enum_MetroColour_Foreground = MetroColour::white,
                         $enum_MetroPopoverPosition = MetroPopoverPosition::top, $visible = true)
    {
        parent::__construct();
        $popoverAttributes = [];
        $popoverAttributes['class'] = 'popover ' . $enum_MetroPopoverPosition . ' bg-' . $enum_MetroColour_Background;
        $popoverAttributes['id'] = $id;
        if($visible == false)
            $popoverAttributes['style'] = 'display:none;';
        $this->addElement('div', '', [], $popoverAttributes);
        $this->addElement('div', $text, [0], ['class' => 'fg-' . $enum_MetroColour_Foreground]);
    }
}