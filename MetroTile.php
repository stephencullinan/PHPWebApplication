<?php

class MetroTile extends MetroComponent
{
    function __construct($id, $enum_MetroTileSize = MetroTileSize::square, MetroComponent $control, $onClickEvent = '', $tileLabel = '', $tileSelected = false,
                         $enum_MetroColourBackground = MetroColour::cyan, $enum_MetroColourForeground = MetroColour::white)
    {
        parent::__construct();
        $classDescription = 'tile-' . $enum_MetroTileSize .' bg-' . $enum_MetroColourBackground . ' fg-' . $enum_MetroColourForeground;
        if($tileSelected == true)
            $classDescription .= ' element-selected';
        $this->addElement('div', '', [], ['class' => $classDescription, 'data-role' => 'tile', 'id' => $id, 'onclick' => $onClickEvent, 'style' => 'width:100%;']);
        $this->addElement('div', '', [0], ['class' => 'tile-content']);
        if($control != null)
            $this->addControl($control, [0, 0]);
        if(strlen($tileLabel) > 0)
            $this->addElement('div', $tileLabel, [0], ['class' => 'tile-label']);
    }
}