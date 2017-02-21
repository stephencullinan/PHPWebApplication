<?php

class MetroPanel extends MetroComponent
{
    function __construct($id, $title, $contentText, $enum_MetroIcon = MetroIcon::Chrome, $enum_MetroPanelState = MetroPanelState::default, $collapsible = true)
    {
        parent::__construct();
        $panelAttributes = ['class' => 'panel ' . $enum_MetroPanelState, 'id' => $id];
        if($collapsible == true)
            $panelAttributes['data-role'] = 'panel';
        $this->addElement('div', '', [], ['class' => 'panel ' . $enum_MetroPanelState, 'data-role' => 'panel', 'id' => $id]);
        $this->addElement('div', '', [0], ['class' => 'heading']);
        $this->addElement('span', '', [0, 0], ['class' => 'icon mif-' . $enum_MetroIcon]);
        $this->addElement('span', $title, [0, 0], ['class' => 'title']);
        $this->addElement('div', $contentText, [0], ['class' => 'content bg-white']);
    }
}