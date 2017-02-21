<?php

class LogOn extends Base
{
    function __construct()
    {

    }
    function getLogOnPage()
    {
        $logOnPageLayout = new MetroLayout();
        $logOnPageLayout->addRow();
        $username = new MetroTextField('username', 'Please enter your username', 'Please enter your username', 'Your username goes here', MetroIcon::user);
        $password = new MetroTextField('password', 'Please enter your password', 'Please enter your password', 'Your password goes here', MetroIcon::lock, true);
        $usernameLayout = new MetroLayout();
        $usernameLayout->addRow();
        $usernameLayout->addControlToRow($username, 10, 1, 1);
        $passwordLayout = new MetroLayout();
        $passwordLayout->addRow();
        $passwordLayout->addControlToRow($password, 10, 1, 1);
        $logOnAccordion = new MetroAccordion('logOnAccordion');
        $logOnAccordion->addItemAsControl('Your Username', $username, MetroIcon::user);
        $logOnAccordion->addItemAsControl('Your Password', $password, MetroIcon::lock);
        $logOnPageLayout->addControlToRow($logOnAccordion, 12);
        $logInButton = new MetroCommandButton('logInButton', 'Log In', 'Log In Securely', MetroIcon::enter, $this->updateContent('Logging You In Securely',
        ['page' => 'logOn', 'action' => 'logOn'], 'titleUpdatePanel', 'services.php', ['username', 'password']));
        $quitButton = new MetroCommandButton('quitButton', 'Cancel', 'Return To The Home Page', MetroIcon::exit, '', MetroCommandButtonState::danger);
        $logOnPageLayout->addRow();
        $logOnPageLayout->addControlToRow($logInButton, 4, 1, 1);
        $logOnPageLayout->addControlToRow($quitButton, 4, 1, 1);
        return $logOnPageLayout;
    }
    function processLogOn($username, $password)
    {
        $database = new Database('sample');
        $selectedRow = $database->getTableRows('people', ['username', 'firstname', 'lastname', 'password'], ['username' => $username]);
        $userDetails = [];
        if($selectedRow->num_rows > 0)
        {
            foreach ($selectedRow as $aSelectedRow)
                foreach ($aSelectedRow as $aSelectedCell)
                    array_push($userDetails, $aSelectedCell);
        }
        if($userDetails[0] == $username && $userDetails[3] == $password)
            return $userDetails;
        else if($userDetails[0] == $username)
            return [$username];
        else
            return [];
            /*$userDetailsAccordion = new MetroAccordion('userDetailsAccordion');
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
            }
            for($counter = 0; $counter < 3; $counter++)
                $userDetailsLayout->addControlToRow($userDetailsTiles[$counter], 3, 1, 0);
            $userDetailsAccordion->addItemAsControl('Your User Details', $userDetailsLayout, MetroIcon::user);
            return $userDetailsAccordion;
        }
        else
            return $this->getLogOnPage();*/

    }
    /*function getLoggedInContent($userDetails = [])
    {
        return $this->createUserDetailsAccordion($userDetails);
    }*/
    function getMenu($username)
    {
        $loggedInPage = new MetroAccordionWithTiles('loggedInPage', 3, 3, 1, 0);
        $loggedInPage->addTile('Home', MetroIcon::home, $this->updateContent('Opening The Home Page', ['username' => $username, 'page' => 'home']), true);
        $loggedInPage->addTile('Settings', MetroIcon::cog, $this->updateContent('Opening The Settings Page', ['username' => $username, 'page' => 'settings']));
        $loggedInPage->addTile('Log Out', MetroIcon::exit, $this->updateContent('Logging You Out Securely', ['username' => $username, 'page' => 'logOut']));
        $loggedInPage->addTileLayout('Home', MetroIcon::home);
        return $loggedInPage;
    }
    function getMenuForLogOut()
    {
        $titleAccordion = new MetroAccordionWithTiles('title', 3, 3, 1, 0);
        $titleAccordion->addTile('Home', MetroIcon::home, $this->updateContent('Opening The Home Page', ['page' => 'administration']));
        $titleAccordion->addTile('Register', MetroIcon::userplus, $this->updateContent('Opening The Registration Page', ['page' => 'registration']));
        $titleAccordion->addTile('Log On', MetroIcon::enter, $this->updateContent('Opening The Log On Page', ['page' => 'logOn']), true);
        $titleAccordion->addTileLayout('Log On', MetroIcon::enter);
        return $titleAccordion;
    }
}