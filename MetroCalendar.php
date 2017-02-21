<?php

class MetroCalendar extends MetroComponent
{
    function __construct($id, $onClickEvent = '')
    {
        parent::__construct();
        //$this->addElement('script', 'function day_click(short, full){alert("HI");}');
        //$this->addElement('div', '', [], ['data-role' => 'calendar', 'data-week-start' => '1', 'data-buttons' => false, 'id' => $id, 'data-day-click' =>
        //'day_click']);
        $this->addElement('div', '', [], ['data-role' => 'calendar', 'data-week-start' => '1', 'data-buttons' => false, 'data-day-click' => $onClickEvent]);
    }
}