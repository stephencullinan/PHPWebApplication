<?php

class WeighBridge extends Base
{
    function getMainMenu($username)
    {
        $weighBridgeLayout = new MetroLayout();
        $weighBridgeAccordion = new MetroAccordionWithTiles('weighBridgeAccordion', 3, 3, 1, 0);
        $weighBridgeAccordion->addTile('First Weight', MetroIcon::listNumbered, $this->updateContent('Opening The First Weight Menu', ['username' => $username,
        'page' => 'app', 'appTitle' => 'WeighBridge', 'action' => 'createFirstWeight'], 'weighBridgeUpdatePanel'), true);
        $weighBridgeAccordion->addTile('Second Weight', MetroIcon::listNumbered, $this->updateContent('Opening The Second Weight Menu', ['username' => $username,
        'page' => 'app', 'appTitle' => 'WeighBridge', 'action' => 'createSecondWeight'], 'weighBridgeUpdatePanel'));
        $weighBridgeAccordion->addTile('View Dockets', MetroIcon::database, $this->updateContent('Opening The Completed Weighings Menu', ['username' => $username,
        'page' => 'app', 'appTitle' => 'WeighBridge', 'action' => 'viewCompletedWeighings'], 'weighBridgeUpdatePanel'));
        $weighBridgeAccordion->addTileLayout('First Weight', MetroIcon::listNumbered);
        $weighBridgeLayout->addRow();
        $weighBridgeLayout->addControlToRow($weighBridgeAccordion, 12);
        $weighBridgeLayout->addRow();
        $weighBridgeLayout->addControlToRow(new MetroUpdatePanel('weighBridgeUpdatePanel', '', $this->getUsers($username)), 12);
        $weighBridgeMasterUpdatePanel = new MetroUpdatePanel('weighBridgeMasterUpdatePanel', '', $weighBridgeLayout);
        $weighBridgeDocketUpdatePanel = new MetroUpdatePanel('weighBridgeDocketUpdatePanel');
        $weighBridgeMasterLayout = new MetroLayout();
        $weighBridgeMasterLayout->addRow();
        $weighBridgeMasterLayout->addControlToRow($weighBridgeMasterUpdatePanel, 12);
        $weighBridgeMasterLayout->addRow();
        $weighBridgeMasterLayout->addControlToRow($weighBridgeDocketUpdatePanel, 12);
        return $weighBridgeMasterLayout;
    }
    function getCompletedWeightDockets($username)
    {
        $completedWeightDocketsAccordion = new MetroAccordion('completedWeightDocketsAccordion');
        $weighBridgeDatabase = new Database('weighbridge');
        $secondWeighings = $weighBridgeDatabase->getTableRows('secondweights');
        $tileTitleTexts = [];
        $tileIcons = [];
        $tileTouchEvents = [];
        foreach($secondWeighings as $aSecondWeighing)
        {
            array_push($tileTitleTexts, $aSecondWeighing['code']);
            array_push($tileIcons, MetroIcon::truck);
            array_push($tileTouchEvents, $this->hide('weighBridgeMasterUpdatePanel') . $this->show('weighBridgeDocketUpdatePanel') .
            $this->updateContent('Opening The Selected Weight Docket', ['page' => 'app', 'appTitle' => 'WeighBridge', 'username' => $username,
            'action' => 'viewWeightDocket', 'secondWeight' => $aSecondWeighing], 'weighBridgeDocketUpdatePanel'));
        }
        $completedWeightDocketsAccordion->addItemAsControl('Completed Weight Dockets', $this->createTileGroup('completedWeightDockets', $tileTitleTexts, $tileIcons,
        $tileTouchEvents), MetroIcon::truck);
        return $completedWeightDocketsAccordion;
    }
    function getIndividualCompletedWeightDocket($username, $secondWeight)
    {
        //secondWeight":{"code":"2","weight":"45000","date":"2013-01-17 12:32:42","firstweight":"2"}
        $weighBridgeDatabase = new Database('weighbridge');
        $firstWeightDetails = $weighBridgeDatabase->getTableRows('firstweights', [], ['code' => $secondWeight['firstweight']]);
        $tileTitleTexts = ['Back'];
        $tileIcons = [MetroIcon::arrowLeft];
        $tileTouchEvents = [$this->hide('weighBridgeDocketUpdatePanel') . $this->show('weighBridgeMasterUpdatePanel')];
        foreach($firstWeightDetails as $aFirstWeightDetail)
        {
            $driverDetails = $weighBridgeDatabase->getTableRows('drivers', [], ['code' => $aFirstWeightDetail['driver']]);
            $commodityDetails = $weighBridgeDatabase->getTableRows('commodities', [], ['code' => $aFirstWeightDetail['commodity']]);
            foreach($driverDetails as $aDriverDetail)
            {
                array_push($tileTitleTexts, $aDriverDetail['firstname'] . ' ' . $aDriverDetail['lastname']);
                array_push($tileIcons, MetroIcon::user);
                array_push($tileTouchEvents, '');
            }
            foreach($commodityDetails as $aCommodityDetail)
            {
                array_push($tileTitleTexts, $aCommodityDetail['title']);
                array_push($tileIcons, MetroIcon::truck);
                array_push($tileTouchEvents, '');
            }
            array_push($tileTitleTexts, $aFirstWeightDetail['weight']);
            array_push($tileIcons, MetroIcon::truck);
            array_push($tileTouchEvents, '');
            array_push($tileTitleTexts, $aFirstWeightDetail['date']);
            array_push($tileIcons, MetroIcon::calendar);
            array_push($tileTouchEvents, '');
            array_push($tileTitleTexts, '');
            array_push($tileIcons, MetroIcon::truck);
            array_push($tileTouchEvents, '');
            array_push($tileTitleTexts, $secondWeight['weight']);
            array_push($tileIcons, MetroIcon::truck);
            array_push($tileTouchEvents, '');
            array_push($tileTitleTexts, $secondWeight['date']);
            array_push($tileIcons, MetroIcon::calendar);
            array_push($tileTouchEvents, '');
            array_push($tileTitleTexts, $secondWeight['weight'] - $aFirstWeightDetail['weight']);
            array_push($tileIcons, MetroIcon::truck);
            array_push($tileTouchEvents, '');
        }
        $completedWeightDocketAccordion = new MetroAccordion('completedWeightDocketAccordion');
        $completedWeightDocketAccordion->addItemAsControl('Completed Weight Docket', $this->createTileGroup('completedWeightDocket', $tileTitleTexts,
        $tileIcons, $tileTouchEvents), MetroIcon::truck);
        return $completedWeightDocketAccordion;
    }
    function getAvailableDocketsForSecondWeighing($username)
    {
        $weighBridgeDatabase = new Database('weighbridge');
        $secondWeighings = $weighBridgeDatabase->getTableRows('secondweights');
        $firstWeighings = $weighBridgeDatabase->getTableRows('firstweights');
        $secondWeighingsIdentifiers = [];
        $firstWeighingsIdentifiers = [];
        $firstWeighingsWithNoSecondWeights = [];
        foreach($secondWeighings as $aSecondWeighing)
            array_push($secondWeighingsIdentifiers, $aSecondWeighing['firstweight']);
        foreach($firstWeighings as $aFirstWeighing)
            array_push($firstWeighingsIdentifiers, $aFirstWeighing['code']);
        foreach($firstWeighingsIdentifiers as $aFirstWeighingIdentifier)
        {
            if (is_numeric(array_search($aFirstWeighingIdentifier, $secondWeighingsIdentifiers)) == true)
                ;
            else
                array_push($firstWeighingsWithNoSecondWeights, $aFirstWeighingIdentifier);
        }
        $tileTitleTexts = [];
        $tileIcons = [];
        $tileTouchEvents = [];
        foreach($firstWeighingsWithNoSecondWeights as $aValidFirstWeighing)
        {
            $firstWeighingDetails = $weighBridgeDatabase->getTableRows('firstweights', [], ['code' => $aValidFirstWeighing]);
            foreach($firstWeighingDetails as $aFirstWeighingDetails)
            {
                $driverDetails = $weighBridgeDatabase->getTableRows('drivers', [], ['code' => $aFirstWeighingDetails['driver']]);
                $commodityDetails = $weighBridgeDatabase->getTableRows('commodities', [], ['code' => $aFirstWeighingDetails['commodity']]);
                $obtainedDriverDetails = [];
                $obtainedCommodityDetails = [];
                foreach($driverDetails as $aDriverDetails)
                    $obtainedDriverDetails = $aDriverDetails;
                foreach($commodityDetails as $aCommodityDetails)
                    $obtainedCommodityDetails = $aCommodityDetails;
                array_push($tileTitleTexts, $obtainedDriverDetails['firstname'] . ' ' . $obtainedDriverDetails['lastname'] . ' ' .
                $obtainedCommodityDetails['title']);
                array_push($tileIcons, MetroIcon::truck);
                array_push($tileTouchEvents, $this->updateContent('Obtaining The Second Weight', ['page' => 'app', 'appTitle' => 'WeighBridge', 'action' =>
                'obtainSecondWeight', 'username' => $username, 'driverDetails' => $obtainedDriverDetails, 'commodityDetails' => $obtainedCommodityDetails,
                'firstWeightDetails' => $aFirstWeighingDetails], 'weighBridgeUpdatePanel'));
            }
        }
        $secondWeighingsAccordion = new MetroAccordion('secondWeighingsAccordion');
        $secondWeighingsAccordion->addItemAsControl('Second Weighings', $this->createTileGroup('secondWeighings', $tileTitleTexts, $tileIcons, $tileTouchEvents),
        MetroIcon::truck);
        return $secondWeighingsAccordion;
    }
    function getUsers($username)
    {
        $usersAccordion = new MetroAccordion('usersAccordion');
        $weighBridgeDatabase = new Database('weighbridge');
        $drivers = $weighBridgeDatabase->getTableRows('drivers', [], [], 'lastname');
        $tileTitleTexts = ['Add User'];
        $tileIcons = [MetroIcon::userplus];
        $tileTouchEvents = [$this->updateContent('Opening The Add Driver Menu', ['page' => 'app', 'appTitle' => 'WeighBridge', 'action' => 'addNewUser',
        'username' => $username], 'weighBridgeUpdatePanel')];
        foreach($drivers as $aDriver)
        {
            array_push($tileTitleTexts, $aDriver['firstname'] . ' ' . $aDriver['lastname']);
            array_push($tileIcons, MetroIcon::user);
            array_push($tileTouchEvents, $this->updateContent('Opening The Commodities Menu', ['page' => 'app', 'appTitle' => 'WeighBridge',
            'action' => 'viewCommodities', 'username' => $username, 'driverDetails' => ['identifier' => $aDriver['code'], 'firstName' => $aDriver['firstname'],
            'lastName' => $aDriver['lastname']]], 'weighBridgeUpdatePanel'));
        }
        $usersAccordion->addItemAsControl('Drivers', $this->createTileGroup('drivers', $tileTitleTexts, $tileIcons, $tileTouchEvents), MetroIcon::user);
        return $usersAccordion;
    }
    function getCommodities($username, $driverDetails)
    {
        $commoditiesAccordion = new MetroAccordion('commoditiesAccordion');
        $weighBridgeDatabase = new Database('weighbridge');
        $commodities = $weighBridgeDatabase->getTableRows('commodities', [], [], 'title');
        $tileTitleTexts = ['Add Commodity'];
        $tileIcons = [MetroIcon::plus];
        $tileTouchEvents = [$this->updateContent('Opening The Add Commodity Menu', ['page' => 'app', 'appTitle' => 'WeighBridge', 'action' => 'addNewCommodity',
        'username' => $username, 'driverDetails' => $driverDetails], 'weighBridgeUpdatePanel')];
        foreach($commodities as $aCommodity)
        {
            array_push($tileTitleTexts, $aCommodity['title']);
            array_push($tileIcons, MetroIcon::truck);
            //$username, $commodityDetails, $driverDetails
            array_push($tileTouchEvents, $this->updateContent('Obtaining The First Weight', ['page' => 'app', 'appTitle' => 'WeighBridge',
            'action' => 'obtainFirstWeight', 'username' => $username, 'driverDetails' => $driverDetails, 'commodityDetails' => ['code' => $aCommodity['code'],
             'title' => $aCommodity['title']]], 'weighBridgeUpdatePanel'));
        }
        $commoditiesAccordion->addItemAsControl('Commodities', $this->createTileGroup('commodities', $tileTitleTexts, $tileIcons, $tileTouchEvents),
        MetroIcon::truck);
        return $commoditiesAccordion;
    }
    function addNewUser($username)
    {
        $addNewUserLayout = new MetroLayout();
        $addNewUserLayout->addRow();
        $addNewUserLayout->addControlToRow($this->createTextBox('firstName', 'Please enter your first name'), 12);
        $addNewUserLayout->addRow();
        $addNewUserLayout->addControlToRow($this->createTextBox('lastName', 'Please enter your last name'), 12);
        $addNewUserLayout->addRow();
        $addNewUserLayout->addControlToRow(new MetroCommandButton('addNewUserSubmitButton', 'Add User', 'Click Here To Add New User', MetroIcon::checkmark,
        $this->updateContent('Adding New User', ['page' => 'app', 'appTitle' => 'WeighBridge', 'username' => $username, 'action' => 'saveNewUser',
        ], 'weighBridgeUpdatePanel', 'services.php', ['firstName', 'lastName'])), 4, 1, 1);
        $addNewUserLayout->addControlToRow(new MetroCommandButton('addNewUserCancelButton', 'Cancel User', 'Click Here To Cancel Adding User', MetroIcon::exit,
        '', MetroCommandButtonState::danger), 4, 1, 1);
        return $addNewUserLayout;
    }
    function saveNewUser($username, $firstName, $lastName)
    {
        $responseArray = [];
        if(strlen($firstName) == 0)
            $responseArray['error'] = ['title' => 'Invalid First Name', 'content' => 'A first name should have at least 1 character', 'control' => 'firstName'];
        else if(strlen($lastName) == 0)
            $responseArray['error'] = ['title' => 'Invalid Last Name', 'content' => 'A last name should have at least 1 character', 'control' => 'lastName'];
        else
        {
            $weighBridgeDatabase = new Database('weighBridge');
            $uniqueDriverIdentifier = $weighBridgeDatabase->getMaxValueOfColumn('drivers', 'code');
            $weighBridgeDatabase->insertTableRow('drivers', [$uniqueDriverIdentifier + 1, $firstName, $lastName]);
            $responseArray['success'] = ['title' => 'Added Driver', 'content' => 'The driver titled ' . $firstName . ' ' . $lastName .
            ' has been added successfully'];
            $responseArray['html'] = $this->getCommodities($username, ['identifier' => $uniqueDriverIdentifier + 1, 'firstName' => $firstName,
            'lastName' => $lastName])->HTML();
        }
        return $responseArray;
    }
    function addNewCommodity($username, $driverDetails)
    {
        $addNewCommodityLayout = new MetroLayout();
        $addNewCommodityLayout->addRow();
        $addNewCommodityLayout->addControlToRow($this->createTextBox('commodity', 'Please enter the title of the commodity'), 12);
        $addNewCommodityLayout->addRow();
        $addNewCommodityLayout->addControlToRow(new MetroCommandButton('addNewCommoditySubmitButton', 'Add Commodity', 'Click Here To Add New Commodity',
        MetroIcon::checkmark, $this->updateContent('Adding New Commodity', ['page' => 'app', 'appTitle' => 'WeighBridge', 'username' => $username, 'action' =>
        'saveNewCommodity', 'driverDetails' => $driverDetails], 'weighBridgeUpdatePanel', 'services.php', ['commodity'])), 4, 1, 1);
        $addNewCommodityLayout->addControlToRow(new MetroCommandButton('addNewCommodityCancelButton', 'Cancel Commodity', 'Click Here To Cancel Adding Commodity',
        MetroIcon::exit, '', MetroCommandButtonState::danger), 4, 1, 1);
        return $addNewCommodityLayout;
    }
    function saveNewCommodity($username, $commodity, $driverDetails)
    {
        $responseArray = [];
        if(strlen($commodity) == 0)
            $responseArray['error'] = ['title' => 'Invalid Commodity', 'content' => 'A valid commodity should have at least 1 character',
            'control' => 'commodity'];
        else
        {
            $weighBridgeDatabase = new Database('weighBridge');
            $uniqueCommodityIdentifier = $weighBridgeDatabase->getMaxValueOfColumn('commodities', 'code');
            $weighBridgeDatabase->insertTableRow('commodities', [$uniqueCommodityIdentifier + 1, $commodity]);
            $responseArray['success'] = ['title' => 'Added Commodity', 'content' => 'The commodity titled ' . $commodity . ' has been added successfully'];
            $responseArray['html'] = $this->getFirstWeight($username, ['code' => $uniqueCommodityIdentifier + 1, 'title' => $commodity], $driverDetails)->HTML();
        }
        return $responseArray;
    }
    function getFirstWeight($username, $commodityDetails, $driverDetails)
    {
        $weighBridgeDatabase = new Database('weighbridge');
        $uniqueFirstWeightIdentifier = $weighBridgeDatabase->getMaxValueOfColumn('firstweights', 'code');
        $currentTime = new DateTime();
        $currentTimeAsString = $currentTime->format('d/m/y H:i:s');
        $weighBridgeDatabase->insertTableRow('firstweights', [$uniqueFirstWeightIdentifier + 1, $driverDetails['identifier'], $commodityDetails['code'],
        15000, $currentTimeAsString]);
        $firstWeightCompletedAccordion = new MetroAccordion('firstWeightCompletedAccordion');
        $tileTexts = [$driverDetails['firstName'] . ' ' . $driverDetails['lastName'], $commodityDetails['title'], 15000, $currentTimeAsString];
        $tileIcons = [MetroIcon::user, MetroIcon::truck, MetroIcon::listNumbered, MetroIcon::calendar];
        $tileTouchEvents = [];
        $firstWeightCompletedAccordion->addItemAsControl('First Weight Completed', $this->createTileGroup('firstWeightCompleted', $tileTexts, $tileIcons,
        $tileTouchEvents), MetroIcon::truck);
        return $firstWeightCompletedAccordion;
    }
    function getSecondWeight($username, $commodityDetails, $driverDetails, $firstWeightDetails)
    {
        $weighBridgeDatabase = new Database('weighbridge');
        $uniqueSecondWeightIdentifier = $weighBridgeDatabase->getMaxValueOfColumn('secondweights', 'code');
        $currentTime = new DateTime();
        $currentTimeAsString = $currentTime->format('d/m/y H:i:s');
        $weighBridgeDatabase->insertTableRow('secondweights', [$uniqueSecondWeightIdentifier + 1, 45000, $currentTimeAsString, $firstWeightDetails['code']]);
        $secondWeightCompletedAccordion = new MetroAccordion('secondWeightCompletedAccordion');
        $tileTexts = [$driverDetails['firstname'] . ' ' . $driverDetails['lastname'], $commodityDetails['title'], $firstWeightDetails['date'],
        $firstWeightDetails['weight'], $currentTimeAsString, 45000, 45000 - $firstWeightDetails['weight']];
        $tileIcons = [MetroIcon::user, MetroIcon::truck, MetroIcon::calendar, MetroIcon::truck, MetroIcon::calendar, MetroIcon::truck, MetroIcon::truck];
        $tileTouchEvents = [];
        $secondWeightCompletedAccordion->addItemAsControl('Second Weight Completed', $this->createTileGroup('secondWeightCompleted', $tileTexts, $tileIcons,
        $tileTouchEvents), MetroIcon::truck);
        return $secondWeightCompletedAccordion;
    }
}