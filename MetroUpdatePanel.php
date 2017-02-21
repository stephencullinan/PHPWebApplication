<?php

class MetroUpdatePanel extends MetroComponent
{
    function __construct($id, $loadingText = 'Retrieving Your Desired Content', MetroComponent $existingControl = null)
    {
        parent::__construct();
        $this->addElement('div', '', [], ['id' => $id . '_loadingPanel']);
        $loadingAccordion = new MetroAccordion($id . '_loadingAccordion', true, false);
        $loadingAccordionLayout = new MetroLayout();
        $loadingAccordionLayout->addRow();
        $loadingAccordionLayout->addControlToRow(new MetroPreLoader(), 2, 5, 5);
        $loadingAccordion->addItemAsControl($loadingText, $loadingAccordionLayout, MetroIcon::loop2);
        $this->addControl($loadingAccordion, [0]);
        $this->addElement('div', '', [0], ['id' => $id]);
        if($existingControl != null)
            $this->addControl($existingControl, [0, 1]);
    }
}