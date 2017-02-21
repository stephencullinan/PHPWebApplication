<?php

class MetroLayout extends MetroComponent
{
    function __construct()
    {
        parent::__construct();
        $this->addElement('div', '', [], ['class' => 'flex-grid condensed']);
        //$this->addElement('div', '', [], ['class' => 'grid condensed']);
    }
    function addRow()
    {
        $this->addElement('div', '', [0], ['class' => 'row']);
    }
    function addEmptyRow()
    {
        $this->addElement('div', '', [0], ['class' => 'row']);
        $positionOfCurrentRow = $this->getNumberOfElements([0]);
        $this->addElement('div', '', [0, $positionOfCurrentRow], ['class' => 'cell colspan12']);
    }
    function addControlToRow(MetroComponent $control, $widthOfControl, $leftWidth = -1, $rightWidth = -1)
    {
        $positionOfCurrentRow = $this->getNumberOfElements([0]);
        if($leftWidth == -1 && $rightWidth == -1)
        {
            $this->addElement('div', '', [0, $positionOfCurrentRow], ['class' => 'cell colspan' . $widthOfControl]);
            $positionOfCurrentCellInCurrentRow = $this->getNumberOfElements([0, $positionOfCurrentRow]);
            $this->addControl($control, [0, $positionOfCurrentRow, $positionOfCurrentCellInCurrentRow]);
        }
        else
        {
            if($leftWidth > 0)
                $this->addElement('div', '', [0, $positionOfCurrentRow], ['class' => 'cell colspan' . $leftWidth]);
            if($widthOfControl > 0)
            {
                $this->addElement('div', '', [0, $positionOfCurrentRow], ['class' => 'cell colspan' . $widthOfControl]);
                $positionOfCurrentCellInCurrentRow = $this->getNumberOfElements([0, $positionOfCurrentRow]);
                $this->addControl($control, [0, $positionOfCurrentRow, $positionOfCurrentCellInCurrentRow]);
            }
            if($rightWidth > 0)
                $this->addElement('div', '', [0, $positionOfCurrentRow], ['class' => 'cell colspan' . $rightWidth]);
        }
    }
}
/*
 <div class="flex-grid demo-grid">
                <div class="row">
                    <div class="cell colspan8 debug">8</div>
                    <div class="cell colspan4 debug">4</div>
                </div>
                <div class="row">
                    <div class="cell size4 debug">4</div>
                    <div class="cell size4 debug">4</div>
                    <div class="cell size4 debug">4</div>
                </div>
                <div class="row">
                    <div class="cell size2 debug">2</div>
                    <div class="cell size2 debug">2</div>
                    <div class="cell size2 debug">2</div>
                    <div class="cell size2 debug">2</div>
                    <div class="cell size2 debug">2</div>
                    <div class="cell size2 debug">2</div>
                </div>
*/