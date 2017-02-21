<?php

class Register extends Base
{
    function __construct()
    {

    }
    function getRegistrationPage()
    {
        $registrationPageLayout = new MetroLayout();
        $registrationPageLayout->addRow();
        $firstName = new MetroTextField('firstName', 'Please enter your first name', 'Please enter your first name', 'Your first name goes here', MetroIcon::user);
        $lastName = new MetroTextField('lastName', 'Please enter your last name', 'Please enter your last name', 'Your last name goes here', MetroIcon::user);
        $username = new MetroTextField('username', 'Please enter your username', 'Please enter your username', 'Your username goes here', MetroIcon::user);
        $password = new MetroTextField('password', 'Please enter your password', 'Please enter your password', 'Your password goes here', MetroIcon::lock, true);
        $registrationAccordion = new MetroAccordion('registrationAccordion');
        $registrationAccordion->addItemAsControl('Your First Name', $firstName, MetroIcon::user);
        $registrationAccordion->addItemAsControl('Your Last Name', $lastName, MetroIcon::user);
        $registrationAccordion->addItemAsControl('Your Username', $username, MetroIcon::user);
        $registrationAccordion->addItemAsControl('Your Password', $password, MetroIcon::lock);
        $registrationPageLayout->addControlToRow($registrationAccordion, 12);
        $registrationButton = new MetroCommandButton('registrationButton', 'Register', 'Register Your Free Account', MetroIcon::userplus,
        $this->updateContent('Creating Your Account', ['page' => 'registration', 'action' => 'createAccount'], 'titleUpdatePanel', 'services.php',
        ['firstName', 'lastName', 'username', 'password']));
        $quitButton = new MetroCommandButton('quitButton', 'Cancel', 'Return To The Home Page', MetroIcon::exit, '', MetroCommandButtonState::danger);
        $registrationPageLayout->addRow();
        $registrationPageLayout->addControlToRow($registrationButton, 4, 1, 1);
        $registrationPageLayout->addControlToRow($quitButton, 4, 1, 1);
        return $registrationPageLayout;
    }
    function processRegistration($firstName, $lastName, $username, $password)
    {
        $responseArray = [];
        if(strlen($firstName) < 2)
            $responseArray['error'] = ['title' => 'Invalid First Name', 'content' => 'A valid first name should have at least 2 characters', 'control' => 'firstName'];
        else if(strlen($lastName) < 2)
            $responseArray['error'] = ['title' => 'Invalid Last Name', 'content' => 'A valid last name should have at least 2 characters', 'control' => 'lastname'];
        else if(strlen($username) < 2)
            $responseArray['error'] = ['title' => 'Invalid Username', 'content' => 'A valid username should have at least 2 characters', 'control' => 'username'];
        else if(strlen($password) < 2)
            $responseArray['error'] = ['title' => 'Invalid Password', 'content' => 'A valid password should have at least 2 characters', 'control' => 'password'];
        else
        {
            $database = new Database('sample');
            $existingUsername = $database->getTableRows('people', ['username'], ['username' => $username]);
            if($existingUsername->num_rows > 0)
            {
                $responseArray['error'] = ['title' => 'Invalid Username', 'content' => 'This username has already been taken', 'control' => 'username'];
            }
            else
            {
                $largestUserID = $database->getMaxValueOfColumn('people' , 'code');
                $database->insertTableRow('people', [$largestUserID + 1, $username, $password, $firstName, $lastName]);
                $responseArray['html'] = $this->createUserDetailsAccordion([$firstName, $lastName, $username, $password])->HTML();
                $responseArray['success'] = ['title' => 'User Created', 'content' => 'The user has been successfully created'];
                $responseArray['updateContent'] = ['panel' => 'menuUpdatePanel', 'page' => 'services.php', 'loadingMessage' => 'Opening The Menu',
                'parameters' => ['action' => 'updateMenu', 'page' => 'logOn', 'status' => 'loggedIn', 'username' => $username]];
            }
        }
        return $responseArray;
    }
}