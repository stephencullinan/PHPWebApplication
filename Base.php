<?php
class Base
{
    function __construct()
    {

    }
    function HTML()
    {

    }
    function updateContent($loadingMessage = 'Retrieving Your Content', $parameters = [], $panelTitle = 'titleUpdatePanel', $requestedPage = 'services.php',
                           $userInputs = [])
    {
        return 'updateContent("' . $panelTitle . '", "' . $requestedPage . '", "' .  $loadingMessage . '", ' . json_encode($parameters) . ', ' .
        json_encode($userInputs) . ');';
    }
    function hide($control)
    {
        return '$(\'#' . $control . '\').hide();';
    }
    function show($control)
    {
        return '$(\'#' . $control . '\').show();';
    }
    function createTileGroup($tileID, $tileTexts = [], $tileIcons = [], $tileTouchEvents = [])
    {
        $tileGroupLayout = new MetroLayout();
        for($counter = 0; $counter < count($tileTexts); $counter++)
        {
            if($counter % 3 == 0)
                $tileGroupLayout->addRow();
            $currentTileLayout = new MetroLayout();
            $currentTileLayout->addRow();
            $currentTileLayout->addControlToRow(new MetroHeading($tileTexts[$counter]), 12);
            if($tileIcons[$counter])
            {
                $currentTileLayout->addRow();
                $currentTileLayout->addControlToRow(new MetroIconFont($tileIcons[$counter], MetroIconSize::four), 2, 5, 5);
            }
            $tileTouchEvent = '';
            if($tileTouchEvents[$counter])
                $tileTouchEvent = $tileTouchEvents[$counter];
            $tileGroupLayout->addControlToRow(new MetroTile($tileID . '_' . $counter, MetroTileSize::square, $currentTileLayout, $tileTouchEvent), 3, 1, 0);
        }
        return $tileGroupLayout;
    }
    function createUserDetailsAccordion($userDetails)
    {
        $userDetailsAccordion = new MetroAccordion('userDetailsAccordion');
        $userDetailsLayout = new MetroLayout();
        $userDetailsLayout->addRow();
        $userDetailsTiles = [];
        $counter = 0;
        foreach($userDetails as $anUserDetail)
        {
            $userDetailTileLayout = new MetroLayout();
            $userDetailTileLayout->addRow();
            $userDetailTileLayout->addControlToRow(new MetroIconFont(MetroIcon::user, MetroIconSize::four), 2, 5 ,5);
            $userDetailTileLayout->addRow();
            $userDetailTileLayout->addControlToRow(new MetroHeading($anUserDetail, '', MetroHeadingSize::Two), 12);
            $counter++;
            array_push($userDetailsTiles, new MetroTile('userDetailsTile_' . $counter, MetroTileSize::square, $userDetailTileLayout));
            if($counter == 3)
                break;
        }
        for($counter = 0; $counter < 3; $counter++)
            $userDetailsLayout->addControlToRow($userDetailsTiles[$counter], 3, 1, 0);
        $userDetailsAccordion->addItemAsControl('Your User Details', $userDetailsLayout, MetroIcon::user);
        return $userDetailsAccordion;
    }
    function getUserID($username)
    {
        $userCode = '';
        $database = new Database('sample');
        $userDetails = $database->getTableRows('people', ['code'], ['username' => $username]);
        foreach($userDetails as $anUserDetails)
            foreach($anUserDetails as $anUserDetail)
                $userCode = $anUserDetail;
        return $userCode;
    }
    function createErrorMessage($messageText)
    {
        $errorMessageAccordion = new MetroAccordion('errorMessageAccordion');
        $errorMessagePopover = new MetroPopover('errorMessagePopover', $messageText, MetroColour::red);
        $errorMessageLayout = new MetroLayout();
        $errorMessageLayout->addRow();
        $errorMessageLayout->addControlToRow($errorMessagePopover, 8, 2, 2);
        $errorMessageAccordion->addItemAsControl('an error has occurred', $errorMessageLayout, MetroIcon::warning);
        return $errorMessageAccordion;
    }
    function createConfirmationMessage($messageText)
    {
        $confirmationMessageAccordion = new MetroAccordion('confirmationMessageAccordion');
        $confirmationMessagePopover = new MetroPopover('confirmationMessagePopover', $messageText);
        $confirmationMessageLayout = new MetroLayout();
        $confirmationMessageLayout->addRow();
        $confirmationMessageLayout->addControlToRow($confirmationMessagePopover, 8, 2, 2);
        $confirmationMessageAccordion->addItemAsControl('Confirmation Message', $confirmationMessageLayout, MetroIcon::info);
        return $confirmationMessageAccordion;
    }
    function createTextBox($textBoxID, $textBoxMessage)
    {
        $textBoxAccordion = new MetroAccordion($textBoxID . '_Accordion');
        $textBox = new MetroTextField($textBoxID, $textBoxMessage, $textBoxMessage, $textBoxMessage);
        $textBoxAccordion->addItemAsControl($textBoxMessage, $textBox, MetroIcon::pencil);
        return $textBoxAccordion;
    }
}