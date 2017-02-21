<?php

class Property extends Base
{
    function getMainMenu($username)
    {
        $propertyLayout = new MetroLayout();
        $propertyAccordion = new MetroAccordionWithTiles('propertyAccordion', 3, 3, 1, 0);
        $propertyAccordion->addTile('View Properties', MetroIcon::search, '', true);
        $propertyAccordion->addTile('Manage Properties', MetroIcon::home);
        $propertyAccordion->addTileLayout('View Properties', MetroIcon::search);
        $propertyLayout->addRow();
        $propertyLayout->addControlToRow($propertyAccordion, 12);
        $propertyLayout->addRow();
        $propertyLayout->addControlToRow(new MetroUpdatePanel('propertyUpdatePanel'), 12);
        return $propertyLayout;
    }
    function addNewProperty()
    {
        $addPropertyLayout = new MetroLayout();
        $addPropertyAccordion = new MetroAccordion('addPropertyAccordion');
        $addPropertyAccordion->addItemAsControl('Neighbourhood Of The Property', $this->createTextBox('neighbourhood', 'Please enter the neighbourhood 
        of the property'), MetroIcon::map);
        $addPropertyAccordion->addItemAsControl('City Of The Property', $this->createTextBox('city', 'Please enter the city of the property'), MetroIcon::map);
        $addPropertyAccordion->addItemAsControl('State Of The Property', $this->createTextBox('state', 'Please enter the state of the property'), MetroIcon::map);
        $addPropertyAccordion->addItemAsControl('Description Of The Property', $this->createTextBox('description', 'Please enter the description of the property'),
        MetroIcon::map);
        $addPropertyLayout->addRow();
        $addPropertyLayout->addControlToRow($addPropertyAccordion, 12);
        $addPropertyLayout->addRow();
        $addPropertyLayout->addControlToRow(new MetroCommandButton('addPropertySubmitButton', 'Add Property', 'Add This Property', MetroIcon::checkmark, ''), 4, 1, 1);
        $addPropertyLayout->addControlToRow(new MetroCommandButton('addPropertyCancelButton', 'Cancel Property', 'Cancel Adding This Property', MetroIcon::exit, '',
        MetroCommandButtonState::danger), 4, 1, 1);
        return $addPropertyLayout;
    }
    function removeProperty()
    {
        $removePropertyLayout = new MetroLayout();
        $removePropertyLayout->addRow();
        $removePropertyLayout->addControlToRow(new MetroHeading('Are you sure you wish to remove this property'), 12);
        $removePropertyLayout->addRow();
        $removePropertyLayout->addControlToRow(new MetroCommandButton('removePropertySubmitButton', 'Remove Property', 'Confirm Removal Of Property', MetroIcon::bin,
        ''), 4, 1, 1);
        $removePropertyLayout->addControlToRow(new MetroCommandButton('removePropertyCancelButton', 'Cancel', 'Cancel Removal Of Property', MetroIcon::exit,
        '', MetroCommandButtonState::danger), 4, 1, 1);
        $removePropertyAccordion = new MetroAccordion('removePropertyAccordion');
        //$removePropertyAccordion->addItemAsControl('Removal')
    }
}