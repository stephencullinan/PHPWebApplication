<?php

class Forms extends Base
{
    function getForms($username)
    {
        $formsLayout = new MetroLayout();
        $formsAccordion = new MetroAccordionWithTiles('formsAccordion', 3, 3, 1, 0);
        $formsAccordion->addTile('Add Form', MetroIcon::plus, $this->updateContent('Creating A New Form',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'createNewForm'], 'formsUpdatePanel'), true);
        $formsAccordion->addTile('Submit Form', MetroIcon::checkmark, $this->updateContent('Displaying Available Forms',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'displayAvailableForms'], 'formsUpdatePanel'));
        $formsAccordion->addTile('Form Answers', MetroIcon::database, $this->updateContent('Displaying Submitted Forms',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'displaySubmittedForms'], 'formsUpdatePanel'));
        $formsAccordion->addTileLayout('Add Form', MetroIcon::plus);
        $formsLayout->addRow();
        $formsLayout->addControlToRow($formsAccordion, 12);
        $formsLayout->addRow();
        $formsLayout->addControlToRow(new MetroUpdatePanel('formsUpdatePanel', '', $this->createForm($username)), 12);
        return $formsLayout;
    }
    function createForm($username)
    {
        $createFormLayout = new MetroLayout();
        $createFormAccordion = new MetroAccordion('createFormAccordion');
        $formTitle = new MetroTextField('formTitle', 'Please enter the title of the form', 'Please enter the title of the form', 'The title of the form goes here');
        $numberOfPages = new MetroTextField('numberOfPages', 'Please enter the number of pages', 'Please enter the number of pages', 'The number of pages goes here');
        $createFormAccordion->addItemAsControl('Title Of The Form', $formTitle, MetroIcon::info);
        $createFormAccordion->addItemAsControl('Number Of Pages', $numberOfPages, MetroIcon::listNumbered);
        $createFormLayout->addRow();
        $createFormLayout->addControlToRow($createFormAccordion, 12);
        $createFormLayout->addRow();
        $createFormLayout->addControlToRow(new MetroCommandButton('createFormButton', 'Create Form', 'Create Your Form', MetroIcon::floppyDisk,
        $this->updateContent('Creating Your Form', ['username' => $username, 'page' => 'app', 'appTitle' => 'Forms', 'action' => 'saveFormProperties'],
        'formsUpdatePanel', 'services.php', ['formTitle', 'numberOfPages'])), 4, 1, 1);
        $createFormLayout->addControlToRow(new MetroCommandButton('cancelFormButton', 'Cancel', 'Cancel The Form', MetroIcon::exit,
        '', MetroCommandButtonState::danger), 4, 1, 1);
        return $createFormLayout;
    }
    function saveFormProperties($username, $formTitle, $numberOfPages)
    {
        $responseArray = [];
        if(is_string($formTitle) == false || strlen($formTitle) < 2)
            $responseArray['error'] = ['title' => 'Invalid Form Title', 'content' => 'A valid form title should have at least 2 characters',
            'control' => 'formTitle'];
        else if(is_numeric($numberOfPages) == false || $numberOfPages <= 0)
            $responseArray['error'] = ['title' => 'Invalid Number Of Pages', 'content' => 'A valid number of pages should be greater than zero',
            'control' => 'numberOfPages'];
        else
        {
            $database = new Database('sample');
            $userID = '';
            $possibleUserIDs = $database->getTableRows('people', ['code'], ['username' => $username]);
            foreach($possibleUserIDs as $aPossibleUserID)
                foreach ($aPossibleUserID as $currentUserID)
                    $userID = $currentUserID;
            $formDatabase = new Database('forms');
            $maxFormNumber = $formDatabase->getMaxValueOfColumn('forms', 'code');
            $formDatabase->insertTableRow('forms', [$maxFormNumber + 1, $formTitle, $userID]);
            $firstPageNumber = -1;
            for($counter = 0; $counter < $numberOfPages; $counter++)
            {
                $uniquePageNumber = $formDatabase->getMaxValueOfColumn('formpages', 'code');
                if($counter == 0)
                    $firstPageNumber = $uniquePageNumber + 1;
                $formDatabase->insertTableRow('formpages', [$uniquePageNumber + 1, $maxFormNumber + 1, $counter + 1]);
            }
            $responseArray['success'] = ['title' => 'Form Created', 'content' => 'The form has been successfully created'];
            $responseArray['html'] = $this->createPageOfForm(1, $numberOfPages, $username, $firstPageNumber, $maxFormNumber + 1)->HTML();
        }
        return $responseArray;
    }
    function createPageOfForm($currentPage, $totalNumberOfPages, $username, $currentPageNumber, $formNumber)
    {
        $createdFormLayout = new MetroLayout();
        if($currentPage <= $totalNumberOfPages)
        {
            $createdFormLayout->addRow();
            $createdFormLayout->addControlToRow(new MetroProgressBar('currentPageNumber', ($currentPage / $totalNumberOfPages) * 100), 12);
            $createdFormLayout->addRow();
            $availableControls = new MetroListView('availableControls', 'Controls Available For Use');
            $formsDatabase = new Database('forms');
            $availableControlsFromDatabase = $formsDatabase->getTableRows('formcontrols', [], []);
            foreach ($availableControlsFromDatabase as $anControlFromDatabase)
            {
                $availableControls->addListItem($anControlFromDatabase['title'], 'Control', 'Form', 'update("availableControls_Input", "' .
                $anControlFromDatabase['title'] . '");');
            }
            $createdFormLayout->addControlToRow($availableControls, 12);
            $createdFormLayout->addRow();
            $selectedControlTitle = new MetroTextField('selectedControlTitle', 'Please enter the title of the selected control',
            'Please enter the title of the selected control', 'The title of the selected control goes here');
            $selectedControlAccordion = new MetroAccordion('selectedControlAccordion');
            $selectedControlAccordion->addItemAsControl('The title of the selected control', $selectedControlTitle, MetroIcon::pencil);
            $createdFormLayout->addControlToRow($selectedControlAccordion, 12);
            $createdFormLayout->addRow();
            $createdFormLayout->addControlToRow(new MetroCommandButton('submitButton', 'Add Control', 'Add The Control To The Page', MetroIcon::plus,
            $this->updateContent('Updating Page '  . $currentPage . ' Of The Form', ['page' => 'app', 'appTitle' => 'Forms', 'username' => $username,
            'action' => 'addControlToPage', 'currentPageNumber' => $currentPageNumber], 'pageContentUpdatePanel', 'services.php',
            ['availableControls', 'selectedControlTitle']) . 'update("selectedControlTitle_Input", "");'), 4, 1, 1);
            $createdFormLayout->addControlToRow(new MetroCommandButton('cancelButton', 'Cancel', 'Cancel Adding The Control To The Page', MetroIcon::exit,
            '', MetroCommandButtonState::danger), 4, 1, 1);
            $createdFormLayout->addRow();
            $pageOfFormAccordion = new MetroAccordion('pageOfFormAccordion');
            $pageOfFormAccordion->addItemAsControl('Content Of Page ' . $currentPage, new MetroUpdatePanel('pageContentUpdatePanel'), MetroIcon::pencil);
            $createdFormLayout->addControlToRow($pageOfFormAccordion, 12);
            $createdFormLayout->addRow();
            $formPageProperties = $formsDatabase->getTableRows('formpages', ['code'], ['form' => $formNumber, 'page' => $currentPage + 1]);
            $formPageNumber = -1;
            foreach ($formPageProperties as $aFormPageProperty)
                foreach ($aFormPageProperty as $aFormPageIndividualProperty)
                    $formPageNumber = $aFormPageIndividualProperty;
            $createdFormLayout->addControlToRow(new MetroCommandButton('savePageButton', 'Save', 'Save This Page', MetroIcon::floppyDisk,
            $this->updateContent('Opening The Next Page Of The Form', ['page' => 'app', 'appTitle' => 'Forms', 'username' => $username,
            'action' => 'openNextPageOfForm', 'currentPage' => $currentPage + 1, 'totalNumberOfPages' => $totalNumberOfPages, 'currentPageNumber' => $formPageNumber,
            'formNumber' => $formNumber], 'formsUpdatePanel')), 4, 1, 1);
            $createdFormLayout->addControlToRow(new MetroCommandButton('cancelPageButton', 'Cancel', 'Cancel This Page', MetroIcon::exit,
            '', MetroCommandButtonState::danger), 4, 1, 1);
        }
        else
        {
            $formCompletedAccordion = new MetroAccordion('formCompletedAccordion');
            $formCompletedLayout = new MetroLayout();
            $formCompletedLayout->addRow();
            $formCompletedLayout->addControlToRow(new MetroPopover('formCompletedPopover', 'You have successfully completed the form', MetroColour::cyan,
            MetroColour::white, MetroPopoverPosition::top), 6, 3, 3);
            $formCompletedAccordion->addItemAsControl('Form Successfully Completed', $formCompletedLayout, MetroIcon::checkmark);
            $createdFormLayout->addRow();
            $createdFormLayout->addControlToRow($formCompletedAccordion, 12);
        }
        return $createdFormLayout;
    }
    function createControl($currentControlType, $currentControlTitleText, $formPage)
    {
        $responseArray = [];
        if(strlen($currentControlType) == 0)
            $responseArray['error'] = ['title' => 'Invalid Control', 'content' => 'You have not selected a valid control', 'control' => 'availableControls'];
        else if(strlen($currentControlTitleText) == 0)
            $responseArray['error'] = ['title' => 'No Title', 'content' => 'You have not typed a valid title', 'control' => 'selectedControlTitle'];
        else
        {
            $currentControlAccordion = new MetroAccordion('currentControlAccordion');
            $formsDatabase = new Database('forms');
            $currentFormControl = $formsDatabase->getMaxValueOfColumn('formcontent', 'code');
            $currentFormType = $formsDatabase->getTableRows('formcontrols', ['code'], ['title' => $currentControlType]);
            $controlNumber = -1;
            foreach ($currentFormType as $aCurrentFormType)
                foreach ($aCurrentFormType as $anIndividualCurrentFormType)
                    $controlNumber = $anIndividualCurrentFormType;
            if ($controlNumber == -1)
                $responseArray['error'] = ['title' => 'Invalid Control', 'content' => 'The selected control is not valid'];
            else
            {
                $formsDatabase->insertTableRow('formcontent', [$currentFormControl + 1, $formPage, $controlNumber, $currentControlTitleText]);
                $responseArray['success'] = ['title' => 'Added Control', 'content' => 'The control has been added successfully to the page'];
            }
            if ($currentControlType == 'TextField')
            {
                $currentControlTextField = new MetroTextField('currentControlTextField', $currentControlTitleText, $currentControlTitleText, $currentControlTitleText);
                $currentControlAccordion->addItemAsControl($currentControlTitleText, $currentControlTextField, MetroIcon::pencil);
            }
            else if ($currentControlType == 'Calendar')
            {
                ;
            }
            else if ($currentControlType == 'Toggle')
            {
                ;
            }
            else
            {
                $currentControlErrorHeading = new MetroHeading('You have not added a valid control to the page');
                $currentControlAccordion->addItemAsControl('Invalid Control', $currentControlErrorHeading, MetroIcon::warning);
            }
            $responseArray['addToHTML'] = $currentControlAccordion->HTML();
        }
        return $responseArray;
    }
    function getAvailableForms($username)
    {
        $availableFormsLayout = new MetroLayout();
        $availableFormsLayout->addRow();
        $selectedUserID = $this->getUserID($username);
        $formsDatabase = new Database('forms');
        $formTitles = [];
        $formIcons = [];
        $formTouchEvents = [];
        $createdForms = $formsDatabase->getTableRows('forms', [], ['Author' => $selectedUserID]);
        foreach($createdForms as $aCreatedForm)
        {
            array_push($formTitles, $aCreatedForm['title']);
            array_push($formIcons, MetroIcon::pencil);
            array_push($formTouchEvents, $this->updateContent('Retrieving The Submitted Answers To The Form', ['page' => 'app', 'appTitle' => 'Forms',
            'username' => $username, 'action' => 'viewAnswersForSubmittedForm', 'formID' => $aCreatedForm['code']], 'formsUpdatePanel'));
        }
        $availableFormsAccordion = new MetroAccordion('availableFormsAccordion');
        $availableFormsAccordion->addItemAsControl('Available Forms', $this->createTileGroup('createdForms', $formTitles, $formIcons, $formTouchEvents),
        MetroIcon::pencil);
        return $availableFormsAccordion;
    }
    function getAllForms($username)
    {
        $formsDatabase = new Database('forms');
        $formTitles = [];
        $formIcons = [];
        $formTouchEvents = [];
        $allForms = $formsDatabase->getTableRows('forms', [], []);
        foreach($allForms as $aCreatedForm)
        {
            array_push($formTitles, $aCreatedForm['title']);
            array_push($formIcons, MetroIcon::pencil);
            array_push($formTouchEvents, $this->updateContent('Opening The Selected Form', ['page' => 'app', 'appTitle' => 'Forms', 'username' => $username,
            'action' => 'openSelectedForm', 'formID' => $aCreatedForm['code']], 'formsUpdatePanel'));
        }
        $allFormsAccordion = new MetroAccordion('allFormsAccordion');
        $allFormsAccordion->addItemAsControl('All Forms', $this->createTileGroup('createdForms', $formTitles, $formIcons, $formTouchEvents), MetroIcon::pencil);
        return $allFormsAccordion;
    }
    function getFormPages($username, $formID)
    {
        $formsDatabase = new Database('forms');
        $formPages = $formsDatabase->getTableRows('formpages', [], ['form' => $formID]);
        if($formPages->num_rows > 0)
        {
            foreach($formPages as $aFormPage)
                return $this->getFormPage($username, $formID, $aFormPage['code'], 1, $formPages->num_rows);
        }
        return $this->createErrorMessage('No Pages Of The Form Could Be Located');
    }
    function submitFormPage($username, $formID, $formPageID, $currentPage, $totalNumberOfPages, $formPageAnswers)
    {
        $responseArray = [];
        $formPageAnswersIdentifiers = array_keys($formPageAnswers);
        $userCode = $this->getUserID($username);
        foreach($formPageAnswersIdentifiers as $aFormPageAnswerIdentifier)
        {
            if(strlen($formPageAnswers[$aFormPageAnswerIdentifier]) > 0)
            {
                $formsDatabase = new Database('forms');
                $maxValue = $formsDatabase->getMaxValueOfColumn('formanswers', 'code');
                $formsDatabase->insertTableRow('formanswers', [$maxValue + 1, substr($aFormPageAnswerIdentifier, 8), $formPageAnswers[$aFormPageAnswerIdentifier],
                $userCode]);
            }
            else
            {
                $responseArray['error'] = ['title' => 'Invalid Form Answer', 'content' => 'The form answer you entered is too short',
                'control' => $aFormPageAnswerIdentifier];
                break;
            }
        }
        if(array_key_exists('error', $responseArray) == false)
        {
            $responseArray['success'] = ['title' => 'Form Page Submitted', 'content' => 'Page ' . $currentPage . ' of the form has been successfully submitted'];
            if ($currentPage < $totalNumberOfPages)
            {
                $formsDatabase = new Database('forms');
                $formPages = $formsDatabase->getTableRows('formpages', ['code'], ['form' => $formID]);
                $formPageReached = false;
                foreach ($formPages as $aFormPage)
                {
                    if ($formPageReached == true)
                    {
                        $responseArray['html'] = $this->getFormPage($username, $formID, $aFormPage['code'], $currentPage + 1, $totalNumberOfPages)->HTML();
                        break;
                    }
                    if ($aFormPage['code'] == $formPageID)
                        $formPageReached = true;
                }
                if ($formPageReached == false)
                    $responseArray['html'] = $this->createErrorMessage('The next page has not been found')->HTML();
            }
            else
                $responseArray['html'] = $this->createConfirmationMessage('This form has been successfully completed')->HTML();
        }
        return $responseArray;
    }
    function getFormPage($username, $formID, $formPageID, $currentPage, $totalNumberOfPages)
    {
        $availableControls = [];
        $formsDatabase = new Database('forms');
        $formPageContent = $formsDatabase->getTableRows('formcontent', [], ['formpage' => $formPageID]);
        $formPageLayout = new MetroLayout();
        $formPageLayout->addRow();
        $formPageProgressBar = new MetroProgressBar('formPageProgressBar', ($currentPage / $totalNumberOfPages) * 100);
        $formPageLayout->addControlToRow($formPageProgressBar, 12);
        foreach($formPageContent as $aFormPageControl)
        {
            if($aFormPageControl['formcontrol'] == 1)
            {
                $textBoxAccordion = new MetroAccordion('textBoxAccordion_' . $aFormPageControl['code']);
                $textBox = new MetroTextField('textBox_' . $aFormPageControl['code'], $aFormPageControl['title'], $aFormPageControl['title'],
                $aFormPageControl['title']);
                array_push($availableControls, 'textBox_' . $aFormPageControl['code']);
                $textBoxAccordion->addItemAsControl($aFormPageControl['title'], $textBox, MetroIcon::pencil);
                $formPageLayout->addRow();
                $formPageLayout->addControlToRow($textBoxAccordion, 12);
            }
        }
        $submitButton = new MetroCommandButton('submitButton', 'Submit', 'Submit The Page', MetroIcon::checkmark, $this->updateContent('Submitting The Current Page',
        ['page' => 'app', 'appTitle' => 'Forms', 'username' => $username, 'action' => 'submitFormPage', 'formID' => $formID, 'formPageID' => $formPageID,
        'formCurrentPage' => $currentPage, 'totalNumberOfPages' => $totalNumberOfPages], 'formsUpdatePanel', 'services.php', $availableControls));
        $cancelButton = new MetroCommandButton('cancelButton', 'Cancel', 'Cancel The Form', MetroIcon::exit, '', MetroCommandButtonState::danger);
        $formPageLayout->addRow();
        $formPageLayout->addControlToRow($submitButton, 4, 1, 1);
        $formPageLayout->addControlToRow($cancelButton, 4, 1, 1);
        return $formPageLayout;
    }
    function getAnswersForSelectedForm($username, $formID)
    {
        $formAnswersAccordion = new MetroAccordion('formAnswersAccordion');
        $formsDatabase = new Database('forms');
        $formPages = $formsDatabase->getTableRows('formpages', ['code'], ['form' => $formID]);
        foreach($formPages as $aFormPage)
        {
            $formContent = $formsDatabase->getTableRows('formcontent', [], ['formpage' => $aFormPage['code']]);
            foreach($formContent as $aFormContent)
            {
                $formAnswersTable = new MetroTable('formAnswersTable_' . $aFormContent['code']);
                $formAnswersTable->addTableColumns(['Answer', 'Submitted']);
                $formAnswersTableValues = [];
                $formAnswers = $formsDatabase->getTableRows('formanswers', [], ['formcontent' => $aFormContent['code']]);
                foreach($formAnswers as $aFormAnswer)
                {
                    $database = new Database('sample');
                    $username= '';
                    $selectedUsername = $database->getTableRows('people', ['username'], ['code' => $aFormAnswer['user']]);
                    foreach($selectedUsername as $anUsername)
                        foreach($anUsername as $aSelectedUsername)
                            $username = $aSelectedUsername;
                    array_push($formAnswersTableValues, [$aFormAnswer['answer'], $username]);
                }
                $formAnswersTable->addTableRows($formAnswersTableValues);
                $formAnswersAccordion->addItemAsControl('Answers To ' . $aFormContent['title'], $formAnswersTable, MetroIcon::textFile);
            }
        }
        return $formAnswersAccordion;
    }
}