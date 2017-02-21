<?php

class MetroTabs extends MetroComponent
{
    private $id;
    function __construct($id, $tabsOnBottom = false)
    {
        parent::__construct();
        $this->id = $id;
        $classDescription = 'tabcontrol';
        if($tabsOnBottom == true)
            $classDescription .= ' tabs-bottom';
        $this->addElement('div', '', [], ['id' => $id, 'class' => $classDescription, 'data-role' => 'tabcontrol']);
        $this->addElement('ul', '', [0], ['class' => 'tabs']);
        $this->addElement('div', '', [0], ['class' => 'frames']);
    }
    function addTab($tabTitle, $tabContent)
    {
        $this->addElement('li', '', [0, 0]);
        $currentTabPosition = $this->getNumberOfElements([0, 0]);
        $this->addElement('a', $tabTitle, [0, 0, $currentTabPosition], ['href' => '#' . $this->id . '_' . $currentTabPosition]);
        $this->addElement('div', $tabContent, [0, 1], ['class' => 'frame', 'id' => $this->id . '_' . $currentTabPosition]);
    }
}