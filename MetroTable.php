<?php

class MetroTable extends MetroComponent
{
    function __construct($id)
    {
        parent::__construct();
        $this->addElement('table', '', [], ['class' => 'table hovered cell-hovered border bordered', 'id' => $id]);
        $this->addElement('thead', '', [0]);
        $this->addElement('tr', '', [0, 0]);
        $this->addElement('tbody', '', [0]);
    }
    function addTableColumns($tableColumnTitles = [])
    {
        foreach($tableColumnTitles as $aTableColumnTitle)
            $this->addElement('th', $aTableColumnTitle, [0, 0, 0], ['class' => 'sortable-column']);
    }
    function addTableRows($tableRows = [], $rowBackground = true)
    {
        $numberOfCurrentRow = 0;
        foreach($tableRows as $aTableRow)
        {
            $numberOfCurrentRow++;
            $currentRowAttributes = [];
            if($rowBackground == true)
            {
                if($numberOfCurrentRow % 4 == 1)
                    $currentRowAttributes['class'] = 'error';
                else if($numberOfCurrentRow % 4 == 2)
                    $currentRowAttributes['class'] = 'info';
                else if($numberOfCurrentRow % 4 == 3)
                    $currentRowAttributes['class'] = 'success';
                else if($numberOfCurrentRow % 4 == 0)
                    $currentRowAttributes['class'] = 'warning';
            }
            $this->addElement('tr', '', [0, 1], $currentRowAttributes);
            $positionOfCurrentRow = $this->getNumberOfElements([0, 1]);
            foreach($aTableRow as $aTableCell)
                $this->addElement('td', $aTableCell, [0, 1, $positionOfCurrentRow]);
        }
    }
}