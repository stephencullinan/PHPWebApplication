<?php

class MetroListView extends MetroComponent
{
    function __construct($id, $title)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'listview-outlook', 'data-role' => 'listview', 'id' => $id]);
        $this->addElement('div', '', [0], ['class' => 'list-group']);
        $this->addElement('span', $title, [0, 0], ['class' => 'list-group-toggle']);
        $this->addElement('div', '', [0, 0], ['class' => 'list-group-content']);
        $this->addElement('input', '', [0], ['id' => $id . '_Input', 'type' => 'hidden']);
    }
    function addListItem($title, $subTitle, $remark, $onClickEvent = '', $markedListItem = true)
    {
        $listItemClassDescription = 'list';
        if($markedListItem == true)
            $listItemClassDescription .= ' marked';
        $this->addElement('a', '', [0, 0, 1], ['class' => $listItemClassDescription, 'onclick' => $onClickEvent]);
        $positionOfCurrentListItem = $this->getNumberOfElements([0, 0, 1]);
        $this->addElement('div', '', [0, 0, 1, $positionOfCurrentListItem], ['class' => 'list-content']);
        $this->addElement('span', $title, [0, 0, 1, $positionOfCurrentListItem, 0], ['class' => 'list-title']);
        $this->addElement('span', $subTitle, [0, 0, 1, $positionOfCurrentListItem, 0], ['class' => 'list-subtitle']);
        $this->addElement('span', $remark, [0, 0, 1, $positionOfCurrentListItem, 0], ['class' => 'list-remark']);
    }
}