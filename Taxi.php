<?php

class Taxi extends Base
{
    function getMainMenu($username)
    {
        $taxiLayout = new MetroLayout();
        $taxiAccordion = new MetroAccordionWithTiles('taxiAccordion', 3, 3, 1, 0);
        $taxiAccordion->addTile('View Taxis', MetroIcon::search, $this->updateContent('Opening The Search Menu', ['page' => 'app', 'app' => 'Taxi',
        'username' => $username, 'action' => 'openSearchMenu']), true);
        $taxiLayout->addRow();
        $taxiLayout->addControlToRow($taxiAccordion, 12);
        $taxiLayout->addRow();
        $taxiLayout->addControlToRow(new MetroUpdatePanel('taxiUpdatePanel', 'Opening The Search Menu', $this->getSearchMenu($username)), 12);
        return $taxiLayout;
    }
    function getSearchMenu($username)
    {
        $searchMenuLayout = new MetroLayout();
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow($this->createTextBox('fromAddressLineOne', 'Please enter the first line'), 6);
        $searchMenuLayout->addControlToRow($this->createTextBox('toAddressLineOne', 'Please enter the first line'), 6);
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow($this->createTextBox('fromAddressLineTwo', 'Please enter the second line'), 6);
        $searchMenuLayout->addControlToRow($this->createTextBox('toAddressLineTwo', 'Please enter the second line'), 6);
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow($this->createTextBox('fromAddressTown', 'Please enter the town'), 6);
        $searchMenuLayout->addControlToRow($this->createTextBox('toAddressTown', 'Please enter the town'), 6);
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow($this->createTextBox('fromAddressRegion', 'Please enter the region'), 6);
        $searchMenuLayout->addControlToRow($this->createTextBox('toAddressRegion', 'Please enter the region'), 6);
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow($this->createTextBox('fromAddressCountry', 'Please enter the country'), 6);
        $searchMenuLayout->addControlToRow($this->createTextBox('toAddressCountry', 'Please enter the country'), 6);
        $searchMenuLayout->addRow();
        $searchMenuLayout->addControlToRow(new MetroCommandButton('searchTaxiSubmitButton', 'Search Taxi', 'Search Taxi Submit Button', MetroIcon::checkmark,
        ''), 4, 1, 1);
        $searchMenuLayout->addControlToRow(new MetroCommandButton('searchTaxiCancelButton', 'Cancel Search', 'Cancel Search Button', MetroIcon::exit,
        '', MetroCommandButtonState::danger), 4, 1, 1);
        return $searchMenuLayout;
    }
    function getSearchResults($username, $parameters)
    {
        $database = new Database('taxi');
        $searchResults = $database->getTableRows('vehicles', [], ['town' => $parameters['fromAddressTown'], 'region' => $parameters['fromAddressRegion']]);
        $tileTitleTexts = [];
        $tileIcons = [];
        $tileTouchEvents = [];
        foreach($searchResults as $aSearchResult)
        {
            array_push($tileTitleTexts, $aSearchResult['title']);
            array_push($tileIcons, MetroIcon::automobile);
            array_push($tileTouchEvents, '');
        }

    }
}