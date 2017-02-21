<?php

class Settings extends Base
{
    function getSettingsPage($username)
    {
        $settingsWizard = new MetroWizard('settingsWizard');
        /*$database = new Database('sample');
        $userDetails = $database->getTableRows('people', [], ['username' => $username]);
        $userRowTitles = $database->getTableRowTitles('people');
        $formattedUserDetails = [];
        foreach($userDetails as $anUserDetails)
            foreach($anUserDetails as $anUserDetail)
                array_push($formattedUserDetails, $anUserDetail);
        $formattedUserDetailsWithHeadings = [];
        for($counter = 1; $counter < count($formattedUserDetails); $counter++)
            $formattedUserDetailsWithHeadings[$userRowTitles[$counter]] = $formattedUserDetails[$counter];*/
        $settingsWizard->addStep($this->getSettingsFirstPage($username));
        /*$settingsWizard->addStep($this->getSettingsFirstPage($username));
        $settingsWizard->addStep($this->getSettingsFirstPage($username));
        $settingsWizard->addStep($this->getSettingsFirstPage($username));*/
        //return $settingsWizard;
        $settingsSideBar = new MetroSideBar('settingsSideBar');
        $settingsSideBar->addItem('Personal Details', 'Your Personal Details', MetroIcon::user, $this->updateContent('Opening Your Personal Details',
        ['page' => 'settings', 'username' => $username, 'action' => 'openSettingsPage'], 'settingsMasterUpdatePanel'));
        $settingsSideBar->addItem('Addresses', 'Your Addresses', MetroIcon::map, $this->updateContent('Opening Your Addresses',
        ['page' => 'settings', 'username' => $username, 'action' => 'openSettingsAddressesPage'], 'settingsMasterUpdatePanel'));
        $settingsSideBar->addItem('Credit Cards', 'Your Payment Methods', MetroIcon::creditCard, $this->updateContent('Opening Your Credit Cards',
        ['page' => 'settings', 'username' => $username, 'action' => 'openSettingsCreditCardsPage'], 'settingsMasterUpdatePanel'));
        $settingsLayout = new MetroLayout();
        $settingsLayout->addRow();
        $settingsLayout->addControlToRow($settingsSideBar, 2, 0, 0);
        $settingsLayout->addControlToRow(new MetroUpdatePanel('settingsMasterUpdatePanel', '', $this->getSettingsFirstPage($username)), 10, 0, 0);
        return $settingsLayout;
    }
    function getSettingsCreditCardsPage($username)
    {
        $creditCardsPage = new MetroLayout();
        $database = new Database('sample');
        $availableCreditCards = $database->getTableRows('creditcards', [], ['user' => $this->getUserID($username)]);
        if($availableCreditCards->num_rows == 0)
        {
            $creditCardsPage->addRow();
            $creditCardsPageAccordion = new MetroAccordion('creditCardsPageAccordion');
            $creditCardsPageAccordion->addItemAsControl('No Credit Cards', new MetroHeading('There are currently no credit cards available'), MetroIcon::warning);
            $creditCardsPage->addControlToRow($creditCardsPageAccordion, 12);
            $creditCardsPage->addRow();
            $creditCardsPage->addControlToRow($this->createTileGroup('addCreditCard', ['Add Card'], [MetroIcon::plus], [$this->updateContent('Adding Your Credit Card',
            ['page' => 'settings', 'username' => $username, 'action' => 'addCreditCard'], 'settingsMasterUpdatePanel')]), 12);
            return $creditCardsPage;
        }
        $listOfAvailableCreditCardsAccordion = new MetroAccordion('listOfAvailableCreditCardsAccordion');
        $listOfAvailableCreditCards = new MetroListView('listOfAvailableCreditCards', 'Available Credit Cards');
        foreach($availableCreditCards as $anAvailableCreditCard)
        {
            $listOfAvailableCreditCards->addListItem($anAvailableCreditCard['firstname'] . ' ' . $anAvailableCreditCard['lastname'], $anAvailableCreditCard['number'],
            $anAvailableCreditCard['securitycode'] . ' ' . $anAvailableCreditCard['expirymonth'] . ' ' . $anAvailableCreditCard['expiryyear'],
            $this->updateContent('Displaying The Detailed View Of The Selected Credit Card', ['page' => 'settings', 'username' =>
            $username, 'action' => 'displayDetailedViewOfCreditCard', 'creditCardIdentifier' => $anAvailableCreditCard['code']], 'creditCardsUpdatePanel'));
        }
        $listOfAvailableCreditCardsAccordion->addItemAsControl('Available Credit Cards', $listOfAvailableCreditCards, MetroIcon::creditCard);
        $creditCardsPage->addRow();
        $creditCardsPage->addControlToRow($listOfAvailableCreditCardsAccordion, 12);
        $creditCardsPage->addRow();
        $creditCardsPage->addControlToRow(new MetroUpdatePanel('creditCardsUpdatePanel'), 12);
        return $creditCardsPage;
    }
    function getSettingsAddressesPage($username)
    {
        $addressesPage = new MetroLayout();
        $database = new Database('sample');
        $availableAddresses = $database->getTableRows('addresses', [], ['user' => $this->getUserID($username)]);
        if($availableAddresses->num_rows == 0)
        {
            $addressesPage->addRow();
            $addressesPageAccordion = new MetroAccordion('addressesPageAccordion');
            $addressesPageAccordion->addItemAsControl('No Addresses', new MetroHeading('There are currently no addresses available'), MetroIcon::warning);
            $addressesPage->addControlToRow($addressesPageAccordion, 12, 0, 0);
            $addressesPage->addRow();
            $addressesPage->addControlToRow($this->createTileGroup('addAddress', ['Add Address'], [MetroIcon::plus],
            [$this->updateContent('Adding Your Address', ['page' => 'settings', 'username' => $username, 'action' => 'addAddress'], 'settingsMasterUpdatePanel')]), 12);
            return $addressesPage;
        }
        $listOfAvailableAddressesAccordion = new MetroAccordion('listOfAvailableAddressesAccordion');
        $listOfAvailableAddresses = new MetroListView('listOfAvailableAddresses', 'Available Addresses');
        foreach($availableAddresses as $anAvailableAddress)
        {
            $listOfAvailableAddresses->addListItem($anAvailableAddress['firstline'] . ' ' . $anAvailableAddress['secondline'],
            $anAvailableAddress['town'] . ' ' . $anAvailableAddress['region'], $anAvailableAddress['country'],
            $this->updateContent('Displaying The Detailed View Of The Selected Address', ['page' => 'settings', 'username' => $username,
            'action' => 'displayDetailedViewOfAddress', 'addressIdentifier' => $anAvailableAddress['code']], 'addressesUpdatePanel'));
        }
        $listOfAvailableAddressesAccordion->addItemAsControl('Available Addresses', $listOfAvailableAddresses, MetroIcon::map);
        $addressesPage->addRow();
        $addressesPage->addControlToRow($listOfAvailableAddressesAccordion, 12, 0, 0);
        $addressesPage->addRow();
        $addressesPage->addControlToRow(new MetroUpdatePanel('addressesUpdatePanel'), 12, 0, 0);
        return $addressesPage;
    }
    function getDetailedViewOfAddress($username, $addressIdentifier)
    {
        $detailedViewOfAddressAccordion = new MetroAccordion('detailedViewOfAddressAccordion');
        $database = new Database('sample');
        $addressDetails = $database->getTableRows('addresses', [], ['code' => $addressIdentifier, 'user' => $this->getUserID($username)]);
        foreach($addressDetails as $anAddressDetail)
        {
            $detailedViewOfAddressAccordion->addItemAsControl('Detailed View Of Address', $this->createTileGroup('detailedViewOfAddress', [$anAddressDetail['firstline'],
            $anAddressDetail['secondline'], $anAddressDetail['town'], $anAddressDetail['region'], $anAddressDetail['country'], $anAddressDetail['phonenumber']],
            [MetroIcon::map, MetroIcon::map, MetroIcon::map, MetroIcon::map, MetroIcon::map, MetroIcon::phone], ['', '', '', '', '', '']), MetroIcon::map);
            $detailedViewOfAddressAccordion->addItemAsControl('Manage Address', $this->createTileGroup('manageAddress', ['Add', 'Delete'],
            [MetroIcon::plus, MetroIcon::bin], [$this->updateContent('Adding Your Address', ['page' => 'settings', 'username' => $username, 'action' => 'addAddress'],
            'settingsMasterUpdatePanel'), $this->updateContent('Remove Your Address', ['page' => 'settings', 'username' => $username, 'action' => 'removeAddress',
            'addressIdentifier' => $anAddressDetail['code']], 'settingsMasterUpdatePanel')]),
            MetroIcon::map);
        }
        return $detailedViewOfAddressAccordion;
    }
    function getDetailedViewOfCreditCard($username, $creditCardIdentifier)
    {
        $detailedViewOfCreditCardAccordion = new MetroAccordion('detailedViewOfCreditCardAccordion');
        $database = new Database('sample');
        $creditCardDetails = $database->getTableRows('creditcards', [], ['code' => $creditCardIdentifier, 'user' => $this->getUserID($username)]);
        foreach($creditCardDetails as $aCreditCardDetail)
        {
            $detailedViewOfCreditCardAccordion->addItemAsControl('Detailed View Of Credit Card', $this->createTileGroup('detailedViewOfCreditCard',
            [$aCreditCardDetail['firstname'], $aCreditCardDetail['lastname'], $aCreditCardDetail['number'], $aCreditCardDetail['securitycode'],
            $aCreditCardDetail['expirymonth'], $aCreditCardDetail['expiryyear']], [MetroIcon::creditCard, MetroIcon::creditCard, MetroIcon::creditCard,
            MetroIcon::creditCard, MetroIcon::creditCard, MetroIcon::creditCard], ['', '', '', '', '', '']), MetroIcon::creditCard);
            $detailedViewOfCreditCardAccordion->addItemAsControl('Manage Credit Card', $this->createTileGroup('manageCreditCard', ['Add', 'Delete'], [MetroIcon::plus,
            MetroIcon::bin], [$this->updateContent('Adding Your Credit Card', ['page' => 'settings', 'username' => $username, 'action' => 'addCreditCard'],
            'settingsMasterUpdatePanel'), $this->updateContent('Remove Your Credit Card', ['page' => 'settings', 'username' => $username, 'action' => 'removeCreditCard',
            'creditCardIdentifier' => $aCreditCardDetail['code']], 'settingsMasterUpdatePanel')]), MetroIcon::creditCard);
        }
        return $detailedViewOfCreditCardAccordion;
    }
    function removeCreditCard($username, $creditCardIdentifier)
    {
        $deletedCreditCardAccordion = new MetroAccordion('deletedCreditCardAccordion');
        $database = new Database('sample');
        $database->removeTableRow('creditcards', ['code' => $creditCardIdentifier, 'user' => $this->getUserID($username)]);
        $deletedCreditCardAccordion->addItemAsControl('Deleted Credit Card Successfully', new MetroHeading('The credit card has been deleted successfully'),
        MetroIcon::bin);
        return $deletedCreditCardAccordion;
    }
    function removeAddress($username, $addressIdentifier)
    {
        $deletedAddressAccordion = new MetroAccordion('deletedAddressAccordion');
        $database = new Database('sample');
        $database->removeTableRow('addresses', ['code' => $addressIdentifier, 'user' => $this->getUserID($username)]);
        $deletedAddressAccordion->addItemAsControl('Deleted Address Successfully', new MetroHeading('The address has been deleted successfully'), MetroIcon::bin);
        return $deletedAddressAccordion;
    }
    function addAddress($username)
    {
        $addAddressLayout = new MetroLayout();
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('firstline', 'Please enter the first line of your address'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('secondline', 'Please enter the second line of your address'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('town', 'Please enter the town of your address'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('region', 'Please enter the region of your address'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('country', 'Please enter the country of the address'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow($this->createTextBox('phonenumber', 'Please enter your phone number'), 12);
        $addAddressLayout->addRow();
        $addAddressLayout->addControlToRow(new MetroCommandButton('addAddressSubmitButton', 'Add Address', 'Add Your Address', MetroIcon::checkmark,
        $this->updateContent('Saving Your Address', ['page' => 'settings', 'username' => $username, 'action' => 'addYourAddress'], 'settingsMasterUpdatePanel',
        'services.php', ['firstline', 'secondline', 'town', 'region', 'country', 'phonenumber']), MetroCommandButtonState::success), 4, 1, 1);
        $addAddressLayout->addControlToRow(new MetroCommandButton('cancelAddressSubmitButton', 'Cancel Address', 'Cancel Adding Your Address', MetroIcon::exit, '',
        MetroCommandButtonState::danger), 4, 1, 1);
        return $addAddressLayout;
    }
    function addCreditCard($username)
    {
        $addCreditCardLayout = new MetroLayout();
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('firstname', 'Please enter the first name on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('lastname', 'Please enter the last name on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('number', 'Please enter the number on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('expirymonth', 'Please enter the expiry month on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('expiryyear', 'Please enter the expiry year on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow($this->createTextBox('securitycode', 'Please enter the security code on your card'), 12);
        $addCreditCardLayout->addRow();
        $addCreditCardLayout->addControlToRow(new MetroCommandButton('addCreditCardSubmitButton', 'Add Credit Card', 'Add Your Credit Card', MetroIcon::checkmark,
        $this->updateContent('Saving Your Credit Card', ['page' => 'settings', 'username' => $username, 'action' => 'addYourCreditCard'], 'settingsMasterUpdatePanel',
        'services.php', ['firstname', 'lastname', 'number', 'expirymonth', 'expiryyear', 'securitycode'])), 4, 1, 1);
        $addCreditCardLayout->addControlToRow(new MetroCommandButton('cancelCreditCardCancelButton', 'Cancel Credit Card', 'Cancel Adding Your Credit Card',
        MetroIcon::exit, '', MetroCommandButtonState::danger), 4, 1, 1);
        return $addCreditCardLayout;
    }
    function saveYourCreditCard($username, $parameters)
    {
        $responseArray = [];
        if(strlen($parameters['firstname']) < 2)
            $responseArray['error'] = ['title' => 'Invalid First Name', 'content' => 'A valid first name should contain at least 2 characters', 'control' => 'firstname'];
        else if(strlen($parameters['lastname']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Lasr Name', 'content' => 'A valid last name should contain at least 2 characters', 'control' => 'lastname'];
        else if(strlen($parameters['number']) < 16)
            $responseArray['error'] = ['title' => 'Invalid Credit Card Number', 'content' => 'A valid credit card number should contain at least 16 characters',
            'control' => 'number'];
        else if(strlen($parameters['expirymonth']) < 1)
            $responseArray['error'] = ['title' => 'Invalid Expiry Month', 'content' => 'A valid credit card expiry month should contain at least 1 character',
            'control' => 'expirymonth'];
        else if(strlen($parameters['expiryyear']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Expiry Year', 'content' => 'A valid credit card expiry year should contain at least 2 characters',
            'control' => 'expiryyear'];
        else if(strlen($parameters['securitycode']) < 3)
            $responseArray['error'] = ['title' => 'Invalid Security Code', 'content' => 'A valid credit card security code should contain at least 3 characters',
            'control' => 'securitycode'];
        else
        {
            $database = new Database('sample');
            $maximumCreditCardIdentifier = $database->getMaxValueOfColumn('creditcards', 'code');
            $database->insertTableRow('creditcards', [$maximumCreditCardIdentifier + 1, $this->getUserID($username), $parameters['firstname'], $parameters['lastname'],
            $parameters['number'], $parameters['securitycode'], $parameters['expirymonth'], $parameters['expiryyear']]);
            $responseArray['success'] = ['title' => 'Credit Card Saved', 'content' => 'Your credit card has been saved successfully'];
            $addedCreditCardAccordion = new MetroAccordion('addedCreditCardAccordion');
            $addedCreditCardAccordion->addItemAsControl('Added Credit Card', $this->createTileGroup('addedCreditCard', [$parameters['firstname'], $parameters['lastname'],
            $parameters['number'], $parameters['expirymonth'], $parameters['expiryyear'], $parameters['securitycode']], [MetroIcon::creditCard, MetroIcon::creditCard,
            MetroIcon::creditCard, MetroIcon::creditCard, MetroIcon::creditCard, MetroIcon::creditCard], ['', '', '', '', '', '']), MetroIcon::creditCard);
            $responseArray['html'] = $addedCreditCardAccordion->HTML();
        }
        return $responseArray;
    }
    function saveYourAddress($username, $parameters)
    {
        $responseArray = [];
        if(strlen($parameters['firstline']) < 2)
            $responseArray['error'] = ['title' => 'Invalid First Line', 'content' => 'A valid first line should contain at least 2 characters', 'control' => 'firstline'];
        else if(strlen($parameters['secondline']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Second Line', 'content' => 'A valid second line should contain at least 2 characters',
            'control' => 'secondline'];
        else if(strlen($parameters['town']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Town', 'content' => 'A valid town should contain at least 2 characters', 'control' => 'town'];
        else if(strlen($parameters['region']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Region', 'content' => 'A valid region should contain at least 2 characters', 'control' => 'region'];
        else if(strlen($parameters['country']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Country', 'content' => 'A valid country should contain at least 2 characters', 'control' => 'country'];
        else if(strlen($parameters['phonenumber']) < 2)
            $responseArray['error'] = ['title' => 'Invalid Phone Number', 'content' => 'A valid phone number should contain at least 2 characters',
            'control' => 'phonenumber'];
        else
        {
            $database = new Database('sample');
            $maximumAddressIdentifier = $database->getMaxValueOfColumn('addresses', 'code');
            $database->insertTableRow('addresses', [$maximumAddressIdentifier + 1, $this->getUserID($username), $parameters['firstline'], $parameters['secondline'],
            $parameters['town'], $parameters['region'], $parameters['country'], $parameters['phonenumber']]);
            $responseArray['success'] = ['title' => 'Address Saved', 'content' => 'Your address has been saved successfully'];
            $addedAddressAccordion = new MetroAccordion('addedAddressAccordion');
            $addedAddressAccordion->addItemAsControl('Added Address', $this->createTileGroup('addedAddress', [$parameters['firstline'], $parameters['secondline'],
            $parameters['town'], $parameters['region'], $parameters['country'], $parameters['phonenumber']], [MetroIcon::map, MetroIcon::map, MetroIcon::map,
            MetroIcon::map, MetroIcon::map, MetroIcon::map], ['', '', '', '', '', '']), MetroIcon::map);
            $responseArray['html'] = $addedAddressAccordion->HTML();
        }
        return $responseArray;
    }
    function getSettingsFirstPage($username, $includePanel = true)
    {
        $database = new Database('sample');
        $userDetails = $database->getTableRows('people', [], ['username' => $username]);
        $userRowTitles = $database->getTableRowTitles('people');
        $formattedUserDetails = [];
        foreach($userDetails as $anUserDetails)
            foreach($anUserDetails as $anUserDetail)
                array_push($formattedUserDetails, $anUserDetail);
        $formattedUserDetailsWithHeadings = [];
        for($counter = 1; $counter < count($formattedUserDetails); $counter++)
            $formattedUserDetailsWithHeadings[$userRowTitles[$counter]] = $formattedUserDetails[$counter];
        if($includePanel == true)
            return $this->createUpdatePanel(1, 4, 'Personal Details', $formattedUserDetailsWithHeadings, 1, $username, $formattedUserDetails[0]);
        else
            return $this->createControlForUpdatePanel(1, 4, 'Personal Details', $formattedUserDetailsWithHeadings, 1, $username, $formattedUserDetails[0]);
    }
    function getEditScreen($title, $value, $uniqueIdentifier, $username)
    {
        $editSettingAccordion = new MetroAccordion('editSettingAccordion');
        $editSettingLayout = new MetroLayout();
        $editSettingLayout->addRow();
        $editSettingLayout->addControlToRow(new MetroTextField('editSettingUpdatedValue', 'The Updated Value For ' . $title, 'The Updated Value For ' . $title,
        'The Updated Value For ' . $title, MetroIcon::pencil, false, $value), 12);
        $editSettingLayout->addRow();
        $saveUpdatedSetting = new MetroCommandButton('saveUpdatedSetting', 'Save', 'Save Your Updated Setting', MetroIcon::floppyDisk,
        $this->updateContent('Saving Your Setting', ['page' => 'settings', 'action' => 'saveSettingsPage', 'title' => $title, 'uniqueIdentifier' => $uniqueIdentifier,
        'username' => $username], 'settingsUpdatePanel_1', 'services.php', ['editSettingUpdatedValue']));
        $cancelUpdatedSetting = new MetroCommandButton('cancelUpdatedSetting', 'Cancel', 'Cancel Your Updated Setting', MetroIcon::exit,
        $this->updateContent('Returning to the Settings Page', ['page' => 'settings', 'action' => 'openSettingsPage', 'username' => $username],
        'settingsUpdatePanel_1'), MetroCommandButtonState::danger);
        $editSettingLayout->addControlToRow($saveUpdatedSetting, 4, 1, 1);
        $editSettingLayout->addControlToRow($cancelUpdatedSetting, 4, 1, 1);
        $editSettingAccordion->addItemAsControl('The Updated Value For ' . $title, $editSettingLayout, MetroIcon::pencil);
        return $editSettingAccordion;
    }
    function saveSetting($uniqueIdentifier, $title, $value, $username)
    {
        $database = new Database('sample');
        $database->updateTableRow('people', [$title => $value], ['code' => $uniqueIdentifier]);
        return $this->getSettingsFirstPage($username, false);
    }
    private function createUpdatePanel($currentLocation, $totalLocation, $title, $attributes, $panelNumber, $username, $uniqueIdentifier)
    {
        $settingsUpdatePanel = new MetroUpdatePanel('settingsUpdatePanel_' . $panelNumber, 'Opening The Requested Page Of The Menu',
        $this->createControlForUpdatePanel($currentLocation, $totalLocation, $title, $attributes, $panelNumber, $username, $uniqueIdentifier));
        return $settingsUpdatePanel;
    }
    private function createControlForUpdatePanel($currentLocation, $totalLocation, $title, $attributes, $panelNumber, $username, $uniqueIdentifier)
    {
        $settingsLayout = new MetroLayout();
        //$settingsLayout->addRow();
        //$settingsProgressBar = new MetroProgressBar('settingsProgressBar', ($currentLocation / $totalLocation) * 100);
        //$settingsLayout->addControlToRow($settingsProgressBar, 12);
        $attributeIdentifiers = array_keys($attributes);
        $formattedTiles = [];
        foreach($attributeIdentifiers as $anAttributeIdentifier)
        {
            $formattedTileLayout = new MetroLayout();
            $formattedTileLayout->addRow();
            $formattedTileLayout->addControlToRow(new MetroIconFont(MetroIcon::user, MetroIconSize::four), 1, 2, 0);
            $formattedTileLayout->addControlToRow(new MetroHeading($anAttributeIdentifier), 9);
            $formattedTileLayout->addRow();
            $formattedTileLayout->addControlToRow(new MetroHeading($attributes[$anAttributeIdentifier]), 12);
            array_push($formattedTiles, new MetroTile('tile_' . $anAttributeIdentifier, MetroTileSize::square, $formattedTileLayout,
            $this->updateContent('Opening The Edit Setting Page For ' . $anAttributeIdentifier, ['page' => 'settings', 'action' => 'editSetting',
            'title' => $anAttributeIdentifier, 'value' => $attributes[$anAttributeIdentifier], 'uniqueIdentifier' => $uniqueIdentifier, 'username' => $username],
            'settingsUpdatePanel_' . $panelNumber)));
        }
        for($counter = 0; $counter < count($formattedTiles); $counter = $counter + 2)
        {
            $settingsLayout->addRow();
            $settingsLayout->addControlToRow($formattedTiles[$counter], 5, 1, 0);
            if($counter + 1 < count($formattedTiles))
                $settingsLayout->addControlToRow($formattedTiles[$counter + 1], 5, 1, 0);
        }
        $settingsAccordion = new MetroAccordion('settingsAccordion_' . $currentLocation);
        $settingsAccordion->addItemAsControl($title, $settingsLayout, MetroIcon::user);
        return $settingsAccordion;
    }
}