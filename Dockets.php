<?php

class Dockets extends Base
{
    function getDockets($username)
    {
        $docketsLayout = new MetroLayout();
        $docketsAccordion = new MetroAccordionWithTiles('docketsAccordion', 3, 3, 1, 0);
        $docketsAccordion->addTile('Add Docket', MetroIcon::plus, $this->updateContent('Creating A New Docket',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets', 'action' => 'createNewDocket'], 'docketsUpdatePanel'));
        $docketsAccordion->addTile('Pay Docket', MetroIcon::euro, $this->updateContent('Displaying Available Dockets',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets', 'action' => 'displayAvailableDockets'], 'docketsUpdatePanel'));
        $docketsAccordion->addTile('View Dockets', MetroIcon::euro, $this->updateContent('Displaying Available Dockets',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets', 'action' => 'displaySubmittedForms'], 'docketsUpdatePanel'), true);
        $docketsAccordion->addTileLayout('View Dockets', MetroIcon::euro);
        $docketsLayout->addRow();
        $docketsLayout->addControlToRow($docketsAccordion, 12);
        $docketsLayout->addRow();
        $docketsLayout->addControlToRow(new MetroUpdatePanel('docketsUpdatePanel', '', $this->viewDockets($username)), 12);
        $masterDocketsLayout = new MetroLayout();
        $masterDocketsLayout->addRow();
        $masterDocketsLayout->addControlToRow(new MetroUpdatePanel('masterDocketsUpdatePanel', '', $docketsLayout), 12);
        $masterDocketsLayout->addRow();
        $masterDocketsLayout->addControlToRow(new MetroUpdatePanel('individualDocketUpdatePanel', ''), 12);
        return $masterDocketsLayout;
    }
    function createNewDocket($username)
    {
        $createNewDocketLayout = new MetroLayout();
        $createNewDocketUsername = new MetroAccordion('createNewDocketUsername');
        $createNewDocketUsername->addItemAsControl('Please enter the recipient of the docket', new MetroTextField('recipient',
        'Please enter the recipient of the docket', 'Please enter the recipient of the docket', 'The recipient of the docket goes here', MetroIcon::user),
        MetroIcon::user);
        $createNewDocketNumberOfItems = new MetroAccordion('createNewDocketNumberOfItems');
        $createNewDocketNumberOfItems->addItemAsControl('Please enter the number of items in the docket', new MetroTextField('numberOfItems',
        'Please enter the number of items in the docket', 'Please enter the number of items in the docket', 'Please enter the number of items in the docket',
        MetroIcon::listNumbered), MetroIcon::listNumbered);
        $createNewDocketLayout->addRow();
        $createNewDocketLayout->addControlToRow($createNewDocketUsername, 12);
        $createNewDocketLayout->addRow();
        $createNewDocketLayout->addControlToRow($createNewDocketNumberOfItems, 12);
        $createNewDocketSubmitButton = new MetroCommandButton('createNewDocketSubmitButton', 'Create', 'Create Your Docket', MetroIcon::floppyDisk,
        $this->updateContent('Creating Your Invoice', ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets', 'action' => 'saveCreatedDocket'],
        'docketsUpdatePanel', 'services.php', ['recipient', 'numberOfItems']));
        $createNewDocketCancelButton = new MetroCommandButton('createNewButtonCancelButton', 'Cancel', 'Cancel Your Docket', MetroIcon::exit, '',
        MetroCommandButtonState::danger);
        $createNewDocketLayout->addRow();
        $createNewDocketLayout->addControlToRow($createNewDocketSubmitButton, 4, 1, 1);
        $createNewDocketLayout->addControlToRow($createNewDocketCancelButton, 4, 1, 1);
        return $createNewDocketLayout;
    }
    function saveNewDocket($username, $docketRecipient, $numberOfItems)
    {
        $responseArray = [];
        $database = new Database('sample');
        $recipientUserCode = '';
        $createdUserCode = '';
        $recipientUserDetails = $database->getTableRows('people', ['code'], ['username' => $docketRecipient]);
        foreach($recipientUserDetails as $aRecipientUserDetails)
            foreach($aRecipientUserDetails as $aRecipientUserDetail)
                $recipientUserCode = $aRecipientUserDetail;
        $createdUserDetails = $database->getTableRows('people', ['code'], ['username' => $username]);
        foreach($createdUserDetails as $aCreatedUserDetails)
            foreach($aCreatedUserDetails as $aCreatedUserDetail)
                $createdUserCode = $aCreatedUserDetail;
        if(strlen($recipientUserCode) == 0)
        {
            $responseArray['error'] = ['title' => 'Invalid Username', 'content' => 'A valid username should be registered to an existing user account',
            'control' => 'recipient'];
        }
        else if(is_numeric($numberOfItems) == false || $numberOfItems < 1)
        {
            $responseArray['error'] = ['title' => 'Invalid Number Of Items', 'content' => 'A valid number of items should be an integer value greater than 0',
            'control' => 'numberOfItems'];
        }
        else
        {
            $docketsDatabase = new Database('dockets');
            $newDocketNumber = $docketsDatabase->getMaxValueOfColumn('docket', 'code');
            $docketsDatabase->insertTableRow('docket', [$newDocketNumber + 1, $createdUserCode, $recipientUserCode, false]);
            $responseArray['success'] = ['title' => 'Docket Successfully Created', 'content' => 'Your Docket To ' . $docketRecipient . ' has been successfully 
            created'];
            $createdDocketLayout = new MetroLayout();
            $createdDocketAccordion = new MetroAccordion('createdDocketAccordion');
            $controlsToBeAdded = [];
            for($counter = 1; $counter <= $numberOfItems; $counter++)
            {
                $itemDescription = new MetroTextField('itemDescription_' . $counter, 'Please enter the description of the item',
                'Please enter the description of the item', 'The description of the item goes here', MetroIcon::textFile);
                $itemQuantity = new MetroTextField('itemQuantity_' . $counter, 'Item Quantity', 'Item Quantity', 'Item Quantity', MetroIcon::listNumbered);
                $itemPrice = new MetroTextField('itemPrice_' . $counter, 'Item Price', 'Item Price', 'Item Price', MetroIcon::listNumbered);
                $currentItemLayout = new MetroLayout();
                $currentItemLayout->addRow();
                $currentItemLayout->addControlToRow($itemDescription, 5, 1, 0);
                $currentItemLayout->addControlToRow($itemQuantity, 2, 1, 0);
                $currentItemLayout->addControlToRow($itemPrice, 2, 1, 0);
                array_push($controlsToBeAdded, 'itemDescription_' . $counter);
                array_push($controlsToBeAdded, 'itemQuantity_' . $counter);
                array_push($controlsToBeAdded, 'itemPrice_' . $counter);
                $createdDocketAccordion->addItemAsControl('The Content For Item ' . $counter, $currentItemLayout, MetroIcon::textFile);
            }
            $submitDocketItemsButton = new MetroCommandButton('submitDocketItemsButton', 'Submit', 'Submit The Items For The Docket', MetroIcon::floppyDisk,
            $this->updateContent('Creating Your Invoice', ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets', 'docketNumber' => $newDocketNumber + 1,
            'action' => 'saveItemsForCreatedDocket'], 'docketsUpdatePanel', 'services.php', $controlsToBeAdded));
            $cancelDocketItemsButton = new MetroCommandButton('cancelDocketItemsButton', 'Cancel', 'Cancel The Items For The Docket', MetroIcon::exit,
            '', MetroCommandButtonState::danger);
            $createdDocketLayout->addRow();
            $createdDocketLayout->addControlToRow($createdDocketAccordion, 12);
            $createdDocketLayout->addRow();
            $createdDocketLayout->addControlToRow($submitDocketItemsButton, 4, 1, 1);
            $createdDocketLayout->addControlToRow($cancelDocketItemsButton, 4, 1, 1);
            $responseArray['html'] = $createdDocketLayout->HTML();
        }
        return $responseArray;
    }
    function saveItemsForNewDocket($username, $docketNumber, $items)
    {
        $responseArray = [];
        $itemIdentifiers = array_keys($items);
        for($counter = 0; $counter < count($items); $counter = $counter + 3)
        {
            if(is_string($items[$itemIdentifiers[$counter]]) == false || strlen($items[$itemIdentifiers[$counter]]) == 0)
            {
                $responseArray['error'] = ['title' => 'Invalid Item Description', 'content' => 'Item Description is not valid',
                'control' => $itemIdentifiers[$counter]];
                break;
            }
            else if(is_numeric($items[$itemIdentifiers[$counter + 1]]) == false || $items[$itemIdentifiers[$counter + 1]] <= 0)
            {
                $responseArray['error'] = ['title' => 'Invalid Item Quantity', 'content' => 'Item Quantity is not valid',
                'control' => $itemIdentifiers[$counter + 1]];
                break;
            }
            else if(is_numeric($items[$itemIdentifiers[$counter + 2]]) == false || $items[$itemIdentifiers[$counter + 2]] <= 0)
            {
                $responseArray['error'] = ['title' => 'Invalid Item Price', 'content' => 'Item Price is not valid',
                'control' => $itemIdentifiers[$counter + 2]];
                break;
            }
        }
        if(count($responseArray) == 0)
        {
            $docketsDatabase = new Database('dockets');
            $tableRows = [];
            $cumulativeTotal = 0;
            for($counter = 0; $counter < count($items); $counter = $counter + 3)
            {
                $currentDocketNumber = $docketsDatabase->getMaxValueOfColumn('docketitem', 'code');
                $docketsDatabase->insertTableRow('docketitem', [$currentDocketNumber + 1, $docketNumber, $items[$itemIdentifiers[$counter]],
                $items[$itemIdentifiers[$counter + 1]], $items[$itemIdentifiers[$counter + 2]]]);
                array_push($tableRows, [$items[$itemIdentifiers[$counter]], $items[$itemIdentifiers[$counter + 1]], $items[$itemIdentifiers[$counter + 2]],
                $items[$itemIdentifiers[$counter + 1]] * $items[$itemIdentifiers[$counter + 2]]]);
                $cumulativeTotal += $items[$itemIdentifiers[$counter + 1]] * $items[$itemIdentifiers[$counter + 2]];
            }
            array_push($tableRows, ['', '', 'Cumulative Total', $cumulativeTotal]);
            $responseArray['success'] = ['Items Saved', 'The items have been successfully saved'];

            $responseArray['html'] = $this->createDocketTable($tableRows)->HTML();
        }
        return $responseArray;
    }
    private function createDocketTable($tableRows)
    {
        $docketsTable = new MetroTable('docketsTable');
        $docketsTable->addTableColumns(['Item Description', 'Item Quantity', 'Item Price', 'Total Price']);
        $docketsTable->addTableRows($tableRows, true);
        $docketsAccordion = new MetroAccordion('docketsAccordion');
        $docketsAccordion->addItemAsControl('Docket Content', $docketsTable, MetroIcon::euro);
        return $docketsAccordion;
    }
    function viewDocket($username, $docketNumber)
    {
        $docketsDatabase = new Database('dockets');
        $docketItems = $docketsDatabase->getTableRows('docketitem', [], ['docket' => $docketNumber]);
        $docketProperties = $docketsDatabase->getTableRows('docket', [], ['code' => $docketNumber]);
        $docketCreator = '';
        $docketAssignee = '';
        foreach($docketProperties as $aDocketProperties)
        {
            $mainDatabase = new Database('sample');
            $docketCreatorProperties = $mainDatabase->getTableRows('people', ['username'], ['code' => $aDocketProperties['created']]);
            foreach($docketCreatorProperties as $aDocketCreatorProperty)
                foreach($aDocketCreatorProperty as $aDocketCreatorPropertyValue)
                    $docketCreator = $aDocketCreatorPropertyValue;
            $docketAssigneeProperties = $mainDatabase->getTableRows('people', ['username'], ['code' => $aDocketProperties['assigned']]);
            foreach($docketAssigneeProperties as $aDocketAssigneeProperty)
                foreach($aDocketAssigneeProperty as $aDocketAssigneePropertyValue)
                    $docketAssignee = $aDocketAssigneePropertyValue;
        }
        $tileTitles = ['Back', $docketCreator, $docketAssignee];
        $tileIcons = [MetroIcon::arrowLeft, MetroIcon::user, MetroIcon::user];
        $tileTouchEvents = [$this->hide('individualDocketUpdatePanel') . $this->show('masterDocketsUpdatePanel'), '', ''];
        $tableRows = [];
        $cumulativeTotal = 0;
        foreach($docketItems as $aDocketItem)
        {
            array_push($tableRows, [$aDocketItem['itemdescription'], $aDocketItem['quantity'], $aDocketItem['price'],
            $aDocketItem['quantity'] * $aDocketItem['price']]);
            $cumulativeTotal += $aDocketItem['quantity'] * $aDocketItem['price'];
        }
        array_push($tableRows, ['', '', 'Cumulative Total', $cumulativeTotal]);
        $docketLayout = new MetroLayout();
        $docketLayout->addRow();
        $docketPropertiesAccordion = new MetroAccordion('docketPropertiesAccordion');
        $docketPropertiesAccordion->addItemAsControl('Docket Properties',
        $this->createTileGroup('viewDocket_' . $docketNumber, $tileTitles, $tileIcons, $tileTouchEvents), MetroIcon::euro);
        $docketLayout->addControlToRow($docketPropertiesAccordion, 12);
        $docketLayout->addRow();
        $docketLayout->addControlToRow($this->createDocketTable($tableRows), 12);
        return $docketLayout;
    }
    function viewDockets($username, $viewCreatedDockets = true)
    {
        $database = new Database('sample');
        $userID = $database->getTableRows('people', ['code'], ['username' => $username]);
        $selectedUserID = '';
        foreach($userID as $anUserID)
            foreach($anUserID as $aSelectedUserID)
                $selectedUserID = $aSelectedUserID;
        $docketsDatabase = new Database('dockets');
        if($viewCreatedDockets == true)
            $docketDetails = $docketsDatabase->getTableRows('docket', [], ['created' => $selectedUserID]);
        else
            $docketDetails = $docketsDatabase->getTableRows('docket', [], ['assigned' => $selectedUserID]);
        $tileTitles = [];
        $tileIcons = [];
        $tileTouchEvents = [];
        foreach($docketDetails as $aDocketDetail)
        {
            array_push($tileTitles, $aDocketDetail['code']);
            array_push($tileIcons, MetroIcon::euro);
            array_push($tileTouchEvents, $this->hide('masterDocketsUpdatePanel') . $this->show('individualDocketUpdatePanel') .
            $this->updateContent('Opening The Docket Numbered ' . $aDocketDetail['code'], ['username' => $username, 'page' => 'app', 'appTitle' => 'Dockets',
            'docketNumber' => $aDocketDetail['code'], 'action' => 'displayDocket'], 'individualDocketUpdatePanel'));
        }
        if(count($tileTitles) > 0)
        {
            $viewDocketsAccordion = new MetroAccordion('viewDocketsAccordion');
            $viewDocketsAccordion->addItemAsControl('Available Dockets',
            $this->createTileGroup('createdDocket', $tileTitles, $tileIcons, $tileTouchEvents), MetroIcon::euro);
            $viewDocketsUpdatePanel = new MetroUpdatePanel('viewDocketsUpdatePanel', 'Displaying The Available Dockets', $viewDocketsAccordion);
            $viewDocketUpdatePanel = new MetroUpdatePanel('viewDocketUpdatePanel', 'Displaying The Selected Docket');
            $viewDocketsLayout = new MetroLayout();
            $viewDocketsLayout->addRow();
            $viewDocketsLayout->addControlToRow($viewDocketsUpdatePanel, 12);
            $viewDocketsLayout->addRow();
            $viewDocketsLayout->addControlToRow($viewDocketUpdatePanel, 12);
            return $viewDocketsLayout;
        }
        else
        {
            $noDocketsAccordion = new MetroAccordion('noDocketsAccordion');
            $noDocketsAccordion->addItemAsControl('No Dockets Are Currently Available', new MetroHeading('There are no dockets currently available'),
            MetroIcon::warning);
            return $noDocketsAccordion;
        }
    }
}