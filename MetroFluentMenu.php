<?php

class MetroFluentMenu extends MetroComponent
{
    function __construct($id)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'fluent-menu', 'data-role' => 'fluentmenu']);
        $this->addElement('ul', '', [0], ['class' => 'tabs-holder']);
        $this->addElement('div', '', [0], ['class' => 'tabs-content']);
    }
    function addTab($tabTitle)
    {
        $this->addElement('li', '', [0, 0], ['class' => 'active']);
        $currentTabPosition = $this->getNumberOfElements([0, 0]);
        $this->addElement('a', $tabTitle, [0, 0, $currentTabPosition], ['href' => '#tab_' . $currentTabPosition]);
        $this->addElement('div', '', [0, 1], ['class' => 'tab-panel', 'id' => 'tab_' . $currentTabPosition]);
    }
    function addTabContent($location, $controls = [], $caption = '')
    {
        $this->addElement('div', '', [0, 1, $location], ['class' => 'tab-panel-group']);
        $this->addElement('div', '', [0, 1, $location, 0], ['class' => 'tab-group-content']);
        $this->addElement('div', '', [0, 1, $location, 0, 0], ['class' => 'tab-content-segment']);
        foreach($controls as $aControl)
            $this->addControl($aControl, [0, 1, $location, 0, 0, 0]);
        $this->addElement('div', $caption, [0, 1, $location, 0], ['class' => 'tab-group-caption']);
    }
}