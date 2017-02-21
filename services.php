<?php
function __autoload($classTitle)
{
    require_once $classTitle . '.php';
}
//if($_POST['action'] && $_POST['action'] == 'logOn')
//{
    /*$responseArray = [];
    $responseHeading = new MetroHeading('SUCCESSFULLY RECEIVED RESPONSE');
    $responseArray['html'] = $responseHeading->HTML();
    echo json_encode($responseArray);*/

    $responseArray = [];
    $inputsArray = json_decode(file_get_contents('php://input', true), true);
    if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'logOn')
    {
        $logOn = new LogOn();
        $responseArray['html'] = $logOn->getLogOnPage()->HTML();
    }
    else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['action'] == 'logOn' && $inputsArray['username'] &&
    $inputsArray['password'] && $inputsArray['page'] == 'logOn')
    {
        $logOn = new LogOn();
        $userDetails = $logOn->processLogOn($inputsArray['username'], $inputsArray['password']);
        if(count($userDetails) == 4)
        {
            $home = new Home();
            $responseArray['html'] = $home->getHomePage($inputsArray['username'])->HTML();
            $responseArray['updateContent'] = ['panel' => 'menuUpdatePanel', 'page' => 'services.php', 'loadingMessage' => 'Opening The Menu',
            'parameters' => ['action' => 'updateMenu', 'page' => 'logOn', 'status' => 'loggedIn', 'username' => $inputsArray['username']]];
            $responseArray['success'] = ['title' => 'Logged In', 'content' => 'You have been logged in as ' . $inputsArray['username']];
        }
        else if(count($userDetails) == 1)
        {
            $responseArray['error'] = ['title' => 'Invalid Password', 'content' => 'You have entered an invalid password', 'control' => 'password'];
        }
        else if(count($userDetails) == 0)
        {
            $responseArray['error'] = ['title' => 'Invalid Username', 'content' => 'You have entered an invalid username', 'control' => 'username'];
            //$responseArray['html'] = $logOn->getLogOnPage();
        }
        //$responseArray['html'] = $logOn->processLogOn($inputsArray['username'], $inputsArray['password'])->HTML();
    }
    //['username' => $username, 'page' => 'logOut']
    else if(count($inputsArray) == 2 && $inputsArray['username'] && $inputsArray['page'] && $inputsArray['page'] == 'logOut')
    {
        $logOn = new LogOn();
        $responseArray['html'] = $logOn->getLogOnPage()->HTML();
        $responseArray['success'] = ['title' => 'Logged Out', 'content' => 'You have been logged out'];
        $responseArray['updateContent'] = ['panel' => 'menuUpdatePanel', 'page' => 'services.php', 'loadingMessage' => 'Opening The Menu',
        'parameters' => ['action' => 'updateMenu', 'page' => 'logOn', 'status' => 'loggedOut', 'username' => $inputsArray['username']]];
    }
    else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'updateMenu' && $inputsArray['page'] &&
    $inputsArray['status'] && $inputsArray['username'] && $inputsArray['page'] == 'logOn' && $inputsArray['status'] == 'loggedIn')
    {
        $logOn = new LogOn();
        $responseArray['html'] = $logOn->getMenu($inputsArray['username'])->HTML();
    }
    else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'updateMenu' && $inputsArray['page'] && $inputsArray['status'] &&
    $inputsArray['username'] && $inputsArray['page'] == 'logOn' && $inputsArray['status'] == 'loggedOut')
    {
        $logOn = new LogOn();
        $responseArray['html'] = $logOn->getMenuForLogOut()->HTML();
    }
    else if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'registration')
    {
        $registration = new Register();
        $responseArray['html'] = $registration->getRegistrationPage()->HTML();
    }
    else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['firstName'] && $inputsArray['lastName'] &&
    $inputsArray['username'] && $inputsArray['password'])
    {
        $registration = new Register();
        $responseArray = $registration->processRegistration($inputsArray['firstName'], $inputsArray['lastName'], $inputsArray['username'], $inputsArray['password']);
    }
    else if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'administration')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->getAdministrationPage()->HTML();
    }
    else if(count($inputsArray) == 5 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['action'] == 'addNewRow' && $inputsArray['table'] &&
    $inputsArray['currentRowNumber'] && $inputsArray['totalRowNumber'] && $inputsArray['page'] == 'administration')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->addRow($inputsArray['table'], $inputsArray['currentRowNumber'], $inputsArray['totalRowNumber'])->HTML();
    }
    else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration' &&
    $inputsArray['action'] == 'addMultipleRows')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->addMultipleRows($inputsArray['table'])->HTML();
    }
    else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'viewTableProperties')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->viewTableProperties($inputsArray['table'])->HTML();
    }
    else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'openTable')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->openSpecifiedTable($inputsArray['table'])->HTML();
    }
    else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'updateMenu')
    {
        $administration = new Administration();
        $responseArray['html'] = $administration->createAdministrationMenu($inputsArray['table'])->HTML();
    }
    /*else if(count($inputsArray) == 2 && $inputsArray['username'] && $inputsArray['page'] && $inputsArray['page'] == 'settings')
    {
        $settings = new Settings();
        $responseArray['html'] = $settings->getSettingsPage($inputsArray['username'])->HTML();
    }
    //'page' => 'settings', 'action' => 'saveSettingsPage', 'title' => $title, 'uniqueIdentifier' => $uniqueIdentifier,'username' => $username]
    else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['uniqueIdentifier'] &&
    $inputsArray['username'] && $inputsArray['page'] == 'settings' && $inputsArray['action'] == 'saveSettingsPage')
    {
        $settings = new Settings();
        $responseArray['html'] = $settings->saveSetting($inputsArray['uniqueIdentifier'], $inputsArray['title'], $inputsArray['editSettingUpdatedValue'],
        $inputsArray['username'])->HTML();
    }
    else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['value']
    && $inputsArray['uniqueIdentifier'] && $inputsArray['username'] && $inputsArray['page'] == 'settings' && $inputsArray['action'] == 'editSetting')
    {
        $settings = new Settings();
        $responseArray['html'] = $settings->getEditScreen($inputsArray['title'], $inputsArray['value'], $inputsArray['uniqueIdentifier'],
        $inputsArray['username'])->HTML();
    }
    else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['username'] && $inputsArray['page'] == 'settings' &&
    $inputsArray['action'] == 'openSettingsPage')
    {
        $settings = new Settings();
        $responseArray['html'] = $settings->getSettingsFirstPage($inputsArray['username'], false)->HTML();
    }*/
    else if(count($inputsArray) >= 2 && $inputsArray['username'] && $inputsArray['page'] && $inputsArray['page'] == 'settings')
    {
        $settings = new Settings();
        if(count($inputsArray) == 2)
            $responseArray['html'] = $settings->getSettingsPage($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 6 && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['uniqueIdentifier'] &&
        $inputsArray['action'] == 'saveSettingsPage')
            $responseArray['html'] = $settings->saveSetting($inputsArray['uniqueIdentifier'], $inputsArray['title'], $inputsArray['editSettingUpdatedValue'],
            $inputsArray['username'])->HTML();
        else if(count($inputsArray) == 6 && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['value'] && $inputsArray['uniqueIdentifier'] &&
        $inputsArray['action'] == 'editSetting')
            $responseArray['html'] = $settings->getEditScreen($inputsArray['title'], $inputsArray['value'], $inputsArray['uniqueIdentifier'],
            $inputsArray['username'])->HTML();
        else if(count($inputsArray) == 3 && $inputsArray['action'] && $inputsArray['action'] == 'openSettingsPage')
            $responseArray['html'] = $settings->getSettingsFirstPage($inputsArray['username'], false)->HTML();
        else if(count($inputsArray) == 3 && $inputsArray['action'] && $inputsArray['action'] == 'openSettingsAddressesPage')
            $responseArray['html'] = $settings->getSettingsAddressesPage($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 3 && $inputsArray['action'] && $inputsArray['action'] == 'addAddress')
            $responseArray['html'] = $settings->addAddress($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 9 && $inputsArray['action'] && $inputsArray['action'] == 'addYourAddress' && $inputsArray['firstline'] && $inputsArray['secondline']
        && $inputsArray['town'] && $inputsArray['region'] && $inputsArray['country'] && $inputsArray['phonenumber'])
        {
            $receivedParameters = $inputsArray;
            unset($receivedParameters['page']);
            unset($receivedParameters['action']);
            unset($receivedParameters['username']);
            $responseArray = $settings->saveYourAddress($inputsArray['username'], $receivedParameters);
        }
        else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'displayDetailedViewOfAddress' &&
        $inputsArray['addressIdentifier'])
            $responseArray['html'] = $settings->getDetailedViewOfAddress($inputsArray['username'], $inputsArray['addressIdentifier'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'removeAddress' && $inputsArray['addressIdentifier'])
            $responseArray['html'] = $settings->removeAddress($inputsArray['username'], $inputsArray['addressIdentifier'])->HTML();
        else if(count($inputsArray) == 3 && $inputsArray['action'] && $inputsArray['action'] == 'openSettingsCreditCardsPage')
            $responseArray['html'] = $settings->getSettingsCreditCardsPage($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 3 && $inputsArray['action'] && $inputsArray['action'] == 'addCreditCard')
            $responseArray['html'] = $settings->addCreditCard($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 9 && $inputsArray['action'] && $inputsArray['action'] == 'addYourCreditCard' && $inputsArray['firstname'] && $inputsArray['lastname']
        && $inputsArray['number'] && $inputsArray['expirymonth'] && $inputsArray['expiryyear'] && $inputsArray['securitycode'])
        {
            $receivedParameters = $inputsArray;
            unset($receivedParameters['page']);
            unset($receivedParameters['action']);
            unset($receivedParameters['username']);
            $responseArray = $settings->saveYourCreditCard($inputsArray['username'], $receivedParameters);
        }
        else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'displayDetailedViewOfCreditCard' && $inputsArray['creditCardIdentifier'])
            $responseArray['html'] = $settings->getDetailedViewOfCreditCard($inputsArray['username'], $inputsArray['creditCardIdentifier'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['action'] == 'removeCreditCard' && $inputsArray['creditCardIdentifier'])
            $responseArray['html'] = $settings->removeCreditCard($inputsArray['username'], $inputsArray['creditCardIdentifier'])->HTML();
    }
    else if(count($inputsArray) == 2 && $inputsArray['page'] && $inputsArray['username'] && $inputsArray['page'] == 'home')
    {
        $home = new Home();
        $responseArray['html'] = $home->getHomePage($inputsArray['username'])->HTML();
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages')
    {
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $messages = new Messages();
            $responseArray['html'] = $messages->getMainMenu($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Messages App<span class="mif-mail icon"></span>'];
        }
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'createNewMessage')
        {
            $messages = new Messages();
            $responseArray['html'] = $messages->sendMessage($inputsArray['username'])->HTML();
        }
        else if (count($inputsArray) == 7 && $inputsArray['recipient'] && $inputsArray['subject'] && $inputsArray['description'] &&
        $inputsArray['action'] == 'sendMessage')
        {
            $messages = new Messages();
            $responseArray = $messages->processMessage($inputsArray['username'], $inputsArray['recipient'], $inputsArray['subject'], $inputsArray['description']);
        }
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'viewReceivedMessages')
        {
            $messages = new Messages();
            $responseArray['html'] = $messages->getMessages($inputsArray['username'])->HTML();
        }
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'viewSentMessages')
        {
            $messages = new Messages();
            $responseArray['html'] = $messages->getMessages($inputsArray['username'], false)->HTML();
        }
        else if (count($inputsArray) == 10 && $inputsArray['back'] && $inputsArray['message'] && $inputsArray['messageID'] && $inputsArray['otherParty'] &&
        $inputsArray['sent'] && $inputsArray['subject'] && $inputsArray['action'] == 'viewDetailedViewOfMessage')
        {
            $messages = new Messages();
            $responseArray['html'] = $messages->displayDetailedMessage($inputsArray['subject'], $inputsArray['message'], $inputsArray['otherParty'],
            $inputsArray['username'], $inputsArray['sent'], $inputsArray['back'])->HTML();
        }
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms')
    {
        $forms = new Forms();
        if (count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $responseArray['html'] = $forms->getForms($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Forms App<span class="mif-pencil icon"></span>'];
        }
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'createNewForm')
            $responseArray['html'] = $forms->createForm($inputsArray['username'])->HTML();
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'displayAvailableForms')
            $responseArray['html'] = $forms->getAllForms($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'openSelectedForm' && $inputsArray['formID'])
            $responseArray['html'] = $forms->getFormPages($inputsArray['username'], $inputsArray['formID'])->HTML();
        else if(count($inputsArray) >= 8 && $inputsArray['action'] == 'submitFormPage' && $inputsArray['formID'] && $inputsArray['formPageID'] &&
        $inputsArray['formCurrentPage'] && $inputsArray['totalNumberOfPages'])
        {
            $inputsArrayRevised = $inputsArray;
            unset($inputsArrayRevised['action']);
            unset($inputsArrayRevised['page']);
            unset($inputsArrayRevised['appTitle']);
            unset($inputsArrayRevised['username']);
            unset($inputsArrayRevised['formID']);
            unset($inputsArrayRevised['formPageID']);
            unset($inputsArrayRevised['formCurrentPage']);
            unset($inputsArrayRevised['totalNumberOfPages']);
            $responseArray = $forms->submitFormPage($inputsArray['username'], $inputsArray['formID'], $inputsArray['formPageID'], $inputsArray['formCurrentPage'],
            $inputsArray['totalNumberOfPages'], $inputsArrayRevised);
        }
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'viewAnswersForSubmittedForm' && $inputsArray['formID'])
            $responseArray['html'] = $forms->getAnswersForSelectedForm($inputsArray['username'], $inputsArray['formID'])->HTML();
        else if (count($inputsArray) == 4 && $inputsArray['action'] == 'displaySubmittedForms')
            $responseArray['html'] = $forms->getAvailableForms($inputsArray['username'])->HTML();
        else if (count($inputsArray) == 6 /*&& $inputsArray['formTitle'] && $inputsArray['numberOfPages']*/ && $inputsArray['action'] == 'saveFormProperties')
            $responseArray = $forms->saveFormProperties($inputsArray['username'], $inputsArray['formTitle'], $inputsArray['numberOfPages']);
        ////{"page":"app","appTitle":"Forms","username":"username","action":"addControlToPage","availableControls":"TextField","selectedControlTitle":"The Title"}
        //{"page":"app","appTitle":"Forms","username":"username","action":"addControlToPage","availableControls":"TextField","selectedControlTitle":"The TextField"}
        else if(count($inputsArray) == 7 && $inputsArray['action'] == 'addControlToPage' && $inputsArray['availableControls'] &&
        $inputsArray['selectedControlTitle'] && $inputsArray['currentPageNumber'])
            $responseArray = $forms->createControl($inputsArray['availableControls'], $inputsArray['selectedControlTitle'], $inputsArray['currentPageNumber']);
        //{"page":"app","appTitle":"Forms","username":"username","action":"openNextPageOfForm","currentPage":2,"totalNumberOfPages":"2","currentPageNumber":"9",
        //"formNumber":6}
        else if(count($inputsArray) == 8 && $inputsArray['action'] == 'openNextPageOfForm' && $inputsArray['currentPage'] && $inputsArray['totalNumberOfPages']
        && $inputsArray['currentPageNumber'] && $inputsArray['formNumber'])
            $responseArray['html'] = $forms->createPageOfForm($inputsArray['currentPage'], $inputsArray['totalNumberOfPages'], $inputsArray['username'],
            $inputsArray['currentPageNumber'], $inputsArray['formNumber'])->HTML();
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Dockets')
    {
        $dockets = new Dockets();
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $responseArray['html'] = $dockets->getDockets($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Dockets App<span class="mif-eur icon"></span>'];
        }
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'createNewDocket')
            $responseArray['html'] = $dockets->createNewDocket($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'displaySubmittedForms')
            $responseArray['html'] = $dockets->viewDockets($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'displayAvailableDockets')
            $responseArray['html'] = $dockets->viewDockets($inputsArray['username'], false)->HTML();
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'displayDocket' && $inputsArray['docketNumber'])
            $responseArray['html'] = $dockets->viewDocket($inputsArray['username'], $inputsArray['docketNumber'])->HTML();
        else if(count($inputsArray) == 6 && $inputsArray['action'] == 'saveCreatedDocket' && $inputsArray['recipient'] && $inputsArray['numberOfItems'])
            $responseArray = $dockets->saveNewDocket($inputsArray['username'], $inputsArray['recipient'], $inputsArray['numberOfItems']);
        else if(count($inputsArray) >= 5 && $inputsArray['action'] == 'saveItemsForCreatedDocket' && $inputsArray['docketNumber'])
        {
            $inputsArrayRevised = $inputsArray;
            unset($inputsArrayRevised['username']);
            unset($inputsArrayRevised['page']);
            unset($inputsArrayRevised['appTitle']);
            unset($inputsArrayRevised['docketNumber']);
            unset($inputsArrayRevised['action']);
            $responseArray = $dockets->saveItemsForNewDocket($inputsArray['username'], $inputsArray['docketNumber'], $inputsArrayRevised);
        }
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Calendar')
    {
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $calendar = new Calendar();
            $responseArray['html'] = $calendar->getMainMenu($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Calendar App<span class="mif-calendar icon"></span>'];
        }
        else if(count($inputsArray) == 8 && $inputsArray['action'] == 'createAppointment' && $inputsArray['day'] && $inputsArray['date']
        && $inputsArray['startTime'] && $inputsArray['endTime'])
        {
            $responseArray['html'] = $calendar->addNewAppointment($inputsArray['username'], $inputsArray['date'], $inputsArray['startTime'],
            $inputsArray['endTime'])->HTML();
        }
        else if(count($inputsArray) == 10 && $inputsArray['action'] == 'saveNewAppointment' && $inputsArray['date'] && $inputsArray['startTime'] &&
        $inputsArray['endTime'] && $inputsArray['title'] && $inputsArray['subTitle'] && $inputsArray['description'])
        {
            $responseArray = $calendar->saveNewAppointment($inputsArray['username'], $inputsArray['date'], $inputsArray['startTime'],
            $inputsArray['endTime'], $inputsArray['title'], $inputsArray['subTitle'], $inputsArray['description']);
        }
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'WeighBridge')
    {
        $weighBridge = new WeighBridge();
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $responseArray['html'] = $weighBridge->getMainMenu($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'WeighBridge App<span class="mif-list-numbered icon"></span>'];
        }
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'createFirstWeight')
            $responseArray['html'] = $weighBridge->getUsers($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'addNewUser')
            $responseArray['html'] = $weighBridge->addNewUser($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 6 && $inputsArray['action'] == 'saveNewUser' && $inputsArray['firstName'] && $inputsArray['lastName'])
            $responseArray = $weighBridge->saveNewUser($inputsArray['username'], $inputsArray['firstName'], $inputsArray['lastName']);
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'viewCommodities' && $inputsArray['driverDetails'])
            $responseArray['html'] = $weighBridge->getCommodities($inputsArray['username'], $inputsArray['driverDetails'])->HTML();
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'addNewCommodity' && $inputsArray['driverDetails'])
            $responseArray['html'] = $weighBridge->addNewCommodity($inputsArray['username'], $inputsArray['driverDetails'])->HTML();
        else if(count($inputsArray) == 6 && $inputsArray['action'] == 'saveNewCommodity' && $inputsArray['driverDetails'] && $inputsArray['commodity'])
            $responseArray = $weighBridge->saveNewCommodity($inputsArray['username'], $inputsArray['commodity'], $inputsArray['driverDetails']);
        else if(count($inputsArray) == 6 && $inputsArray['action'] == 'obtainFirstWeight' && $inputsArray['driverDetails'] && $inputsArray['commodityDetails'])
            $responseArray['html'] = $weighBridge->getFirstWeight($inputsArray['username'], $inputsArray['commodityDetails'], $inputsArray['driverDetails'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'createSecondWeight')
            $responseArray['html'] = $weighBridge->getAvailableDocketsForSecondWeighing($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 7 && $inputsArray['action'] == 'obtainSecondWeight' && $inputsArray['driverDetails'] && $inputsArray['commodityDetails']
        && $inputsArray['firstWeightDetails'])
            $responseArray['html'] = $weighBridge->getSecondWeight($inputsArray['username'], $inputsArray['commodityDetails'], $inputsArray['driverDetails'],
            $inputsArray['firstWeightDetails'])->HTML();
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'viewCompletedWeighings')
            $responseArray['html'] = $weighBridge->getCompletedWeightDockets($inputsArray['username'])->HTML();
        else if(count($inputsArray) == 5 && $inputsArray['action'] == 'viewWeightDocket' && $inputsArray['secondWeight'])
            $responseArray['html'] = $weighBridge->getIndividualCompletedWeightDocket($inputsArray['username'], $inputsArray['secondWeight'])->HTML();
        /*
        $this->updateContent('Opening The Selected Weight Docket', ['page' => 'app', 'appTitle' => 'WeighBridge', 'username' => $username,
            'action' => 'viewWeightDocket', 'secondWeight' => $aSecondWeighing], 'weighBridgeDocketUpdatePanel'));
        */
    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Property')
    {
        $property = new Property();
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $responseArray['html'] = $property->getMainMenu($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Property App<span class="mif-home icon"></span>'];
        }

    }
    else if(count($inputsArray) >= 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Taxi')
    {
        $taxi = new Taxi();
        if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSpecifiedApp')
        {
            $responseArray['html'] = $taxi->getMainMenu($inputsArray['username'])->HTML();
            $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Taxi App<span class="mif-automobile icon"></span>'];
        }
        //['page' => 'app', 'app' => 'Taxi',
        //'username' => $username, 'action' => 'openSearchMenu']
        else if(count($inputsArray) == 4 && $inputsArray['action'] == 'openSearchMenu')
            $responseArray['html'] = $taxi->getSearchMenu($inputsArray['username']);
    }
    echo json_encode($responseArray);
/*
<?php
function __autoload($classTitle)
{
    require_once $classTitle . '.php';
}
//if($_POST['action'] && $_POST['action'] == 'logOn')
//{
$responseArray = [];
$inputsArray = json_decode(file_get_contents('php://input', true), true);
if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'logOn')
{
    $logOn = new LogOn();
    $responseArray['html'] = $logOn->getLogOnPage()->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['username'] && $inputsArray['password'] &&
    $inputsArray['page'] == 'logOn' && $inputsArray['action'] == 'logOn')
{
    $logOn = new LogOn();
    $userDetails = $logOn->processLogOn($inputsArray['username'], $inputsArray['password']);
    if(count($userDetails) == 4)
    {
        $home = new Home();
        $responseArray['html'] = $home->getHomePage($inputsArray['username'])->HTML();
        $responseArray['updateContent'] = ['panel' => 'menuUpdatePanel', 'page' => 'services.php', 'loadingMessage' => 'Opening The Menu',
            'parameters' => ['action' => 'updateMenu', 'page' => 'logOn', 'status' => 'loggedIn', 'username' => $inputsArray['username']]];
        $responseArray['success'] = ['title' => 'Logged In', 'content' => 'You have been logged in as ' . $inputsArray['username']];
    }
    else if(count($userDetails) == 1)
    {
        $responseArray['error'] = ['title' => 'Invalid Password', 'content' => 'You have entered an invalid password', 'control' => 'password'];
    }
    else if(count($userDetails) == 0)
    {
        $responseArray['error'] = ['title' => 'Invalid Username', 'content' => 'You have entered an invalid username', 'control' => 'username'];
        //$responseArray['html'] = $logOn->getLogOnPage();
    }
    //$responseArray['html'] = $logOn->processLogOn($inputsArray['username'], $inputsArray['password'])->HTML();
}
//['username' => $username, 'page' => 'logOut']
else if(count($inputsArray) == 2 && $inputsArray['username'] && $inputsArray['page'] && $inputsArray['page'] == 'logOut')
{
    $logOn = new LogOn();
    $responseArray['html'] = $logOn->getLogOnPage()->HTML();
    $responseArray['success'] = ['title' => 'Logged Out', 'content' => 'You have been logged out'];
    $responseArray['updateContent'] = ['panel' => 'menuUpdatePanel', 'page' => 'services.php', 'loadingMessage' => 'Opening The Menu',
        'parameters' => ['action' => 'updateMenu', 'page' => 'logOn', 'status' => 'loggedOut', 'username' => $inputsArray['username']]];
}
else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['page'] && $inputsArray['status'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'logOn' && $inputsArray['action'] == 'updateMenu' && $inputsArray['status'] == 'loggedIn')
{
    $logOn = new LogOn();
    $responseArray['html'] = $logOn->getMenu($inputsArray['username'])->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['action'] && $inputsArray['page'] && $inputsArray['status'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'logOn' && $inputsArray['action'] == 'updateMenu' && $inputsArray['status'] == 'loggedOut')
{
    $logOn = new LogOn();
    $responseArray['html'] = $logOn->getMenuForLogOut()->HTML();
}
else if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'registration')
{
    $registration = new Register();
    $responseArray['html'] = $registration->getRegistrationPage()->HTML();
}
else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['firstName'] && $inputsArray['lastName'] &&
    $inputsArray['username'] && $inputsArray['password'])
{
    $registration = new Register();
    $responseArray = $registration->processRegistration($inputsArray['firstName'], $inputsArray['lastName'], $inputsArray['username'], $inputsArray['password']);
}
else if(count($inputsArray) == 1 && $inputsArray['page'] && $inputsArray['page'] == 'administration')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->getAdministrationPage()->HTML();
}
else if(count($inputsArray) == 5 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['currentRowNumber'] &&
    $inputsArray['totalRowNumber'] && $inputsArray['page'] == 'administration' && $inputsArray['action'] == 'addNewRow')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->addRow($inputsArray['table'], $inputsArray['currentRowNumber'], $inputsArray['totalRowNumber'])->HTML();
}
else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration' &&
    $inputsArray['action'] == 'addMultipleRows')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->addMultipleRows($inputsArray['table'])->HTML();
}
else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'viewTableProperties')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->viewTableProperties($inputsArray['table'])->HTML();
}
else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'openTable')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->openSpecifiedTable($inputsArray['table'])->HTML();
}
else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['table'] && $inputsArray['page'] == 'administration'
    && $inputsArray['action'] == 'updateMenu')
{
    $administration = new Administration();
    $responseArray['html'] = $administration->createAdministrationMenu($inputsArray['table'])->HTML();
}
else if(count($inputsArray) == 2 && $inputsArray['username'] && $inputsArray['page'] && $inputsArray['page'] == 'settings')
{
    $settings = new Settings();
    $responseArray['html'] = $settings->getSettingsPage($inputsArray['username'])->HTML();
}
//'page' => 'settings', 'action' => 'saveSettingsPage', 'title' => $title, 'uniqueIdentifier' => $uniqueIdentifier,'username' => $username]
else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['uniqueIdentifier'] &&
    $inputsArray['username'] && $inputsArray['page'] == 'settings' && $inputsArray['action'] == 'saveSettingsPage')
{
    $settings = new Settings();
    $responseArray['html'] = $settings->saveSetting($inputsArray['uniqueIdentifier'], $inputsArray['title'], $inputsArray['editSettingUpdatedValue'],
        $inputsArray['username'])->HTML();
}
else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['title'] && $inputsArray['value']
    && $inputsArray['uniqueIdentifier'] && $inputsArray['username'] && $inputsArray['page'] == 'settings' && $inputsArray['action'] == 'editSetting')
{
    $settings = new Settings();
    $responseArray['html'] = $settings->getEditScreen($inputsArray['title'], $inputsArray['value'], $inputsArray['uniqueIdentifier'],
        $inputsArray['username'])->HTML();
}
else if(count($inputsArray) == 3 && $inputsArray['page'] && $inputsArray['action'] && $inputsArray['username'] && $inputsArray['page'] == 'settings' &&
    $inputsArray['action'] == 'openSettingsPage')
{
    $settings = new Settings();
    $responseArray['html'] = $settings->getSettingsFirstPage($inputsArray['username'], false)->HTML();
}
else if(count($inputsArray) == 2 && $inputsArray['page'] && $inputsArray['username'] && $inputsArray['page'] == 'home')
{
    $home = new Home();
    $responseArray['html'] = $home->getHomePage($inputsArray['username'])->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'openSpecifiedApp')
{
    $messages = new Messages();
    $responseArray['html'] = $messages->getMainMenu($inputsArray['username'])->HTML();
    $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Messages App<span class="mif-mail icon"></span>'];
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'createNewMessage')
{
    $messages = new Messages();
    $responseArray['html'] = $messages->sendMessage($inputsArray['username'])->HTML();
}
//{"username":"username","page":"app","appTitle":"Messages","action":"sendMessage","recipient":"","subject":"","description":""}
else if(count($inputsArray) == 7 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['recipient'] && $inputsArray['subject'] && $inputsArray['description'] && $inputsArray['page'] == 'app' &&
    $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'sendMessage')
{
    $messages = new Messages();
    $responseArray = $messages->processMessage($inputsArray['username'], $inputsArray['recipient'], $inputsArray['subject'], $inputsArray['description']);
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'viewReceivedMessages')
{
    $messages = new Messages();
    $responseArray['html'] = $messages->getMessages($inputsArray['username'])->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'viewSentMessages')
{
    $messages = new Messages();
    $responseArray['html'] = $messages->getMessages($inputsArray['username'], false)->HTML();
}
else if(count($inputsArray) == 10 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['back'] && $inputsArray['message'] && $inputsArray['messageID'] && $inputsArray['otherParty'] && $inputsArray['sent'] && $inputsArray['subject'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Messages' && $inputsArray['action'] == 'viewDetailedViewOfMessage')
{
    $messages = new Messages();
    $responseArray['html'] = $messages->displayDetailedMessage($inputsArray['subject'], $inputsArray['message'], $inputsArray['otherParty'],
        $inputsArray['username'], $inputsArray['sent'], $inputsArray['back'])->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms' && $inputsArray['action'] == 'openSpecifiedApp')
{
    $forms = new Forms();
    $responseArray['html'] = $forms->getForms($inputsArray['username'])->HTML();
    $responseArray['toggle'] = ['control' => 'loggedInPage_0', 'text' => 'Forms App<span class="mif-pencil icon"></span>'];
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms' && $inputsArray['action'] == 'createNewForm')
{
    $forms = new Forms();
    $responseArray['html'] = $forms->createForm($inputsArray['username'])->HTML();
}
//$formsAccordion->addTile('Submit Form', MetroIcon::checkmark, $this->updateContent('Displaying Available Forms',
//['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'displayAvailableForms'], 'formsUpdatePanel'));
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms' && $inputsArray['action'] == 'displayAvailableForms')
{
    $forms = new Forms();
    $responseArray['html'] = $forms->getAllForms()->HTML();
}
else if(count($inputsArray) == 4 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms' && $inputsArray['action'] == 'displaySubmittedForms')
{
    $forms = new Forms();
    $responseArray['html'] = $forms->getAvailableForms($inputsArray['username'])->HTML();
}
//{"username":"username","page":"app","appTitle":"Forms","action":"saveFormProperties","formTitle":"","numberOfPages":""}
else if(count($inputsArray) == 6 && $inputsArray['page'] && $inputsArray['appTitle'] && $inputsArray['action'] && $inputsArray['username'] &&
    $inputsArray['formTitle'] && $inputsArray['numberOfPages'] && $inputsArray['page'] == 'app' && $inputsArray['appTitle'] == 'Forms' &&
    $inputsArray['action'] == 'saveFormProperties')
{
    $forms = new Forms();
    $responseArray = $forms->saveFormProperties($inputsArray['username'], $inputsArray['formTitle'], $inputsArray['numberOfPages']);
}
//['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'displaySubmittedForms']
echo json_encode($responseArray);
*/