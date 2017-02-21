<?php

class Messages extends Base
{
    function getMainMenu($username)
    {
        $messagesAccordion = new MetroAccordionWithTiles('messagesAccordion', 3, 3, 1, 0);
        $messagesAccordion->addTile('Send Message', MetroIcon::contactsMail, $this->updateContent('Opening The Send A Message Page',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages', 'action' => 'createNewMessage'], 'messagesUpdatePanel'));
        $messagesAccordion->addTile('Inbox', MetroIcon::mail, $this->updateContent('Opening The Received Messages Page',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages', 'action' => 'viewReceivedMessages'], 'messagesUpdatePanel'), true);
        $messagesAccordion->addTile('Sent Items', MetroIcon::mail, $this->updateContent('Opening The Sent Messages Page',
        ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages', 'action' => 'viewSentMessages'], 'messagesUpdatePanel'));
        $messagesAccordion->addTileLayout('Inbox', MetroIcon::mail);
        $messagesAccordionLayout = new MetroLayout();
        $messagesAccordionLayout->addRow();
        $messagesLayout = new MetroLayout();
        $messagesLayout->addRow();
        $messagesLayout->addControlToRow($messagesAccordion, 12);
        $messagesLayout->addRow();
        $messagesLayout->addControlToRow(new MetroUpdatePanel('messagesUpdatePanel', '', $this->getMessages($username)), 12);
        $messagesAccordionLayout->addRow();
        $messagesAccordionLayout->addControlToRow(new MetroUpdatePanel('mainMenuMessagesUpdatePanel', 'Opening The Main Menu', $messagesLayout), 12);
        $messagesAccordionLayout->addRow();
        $messagesAccordionLayout->addControlToRow(new MetroUpdatePanel('detailedMessageUpdatePanel', 'Opening The Selected Message'), 12);
        return $messagesAccordionLayout;
    }
    function getMessages($username, $receivedMessages = true)
    {
        $parentDatabase = new Database('sample');
        $selectedUserID = '';
        $userID = $parentDatabase->getTableRows('people', ['code'], ['username' => $username]);
        foreach($userID as $anUserID)
            foreach($anUserID as $aSelectedUserID)
                $selectedUserID = $aSelectedUserID;
        $database = new Database('messages');
        if($receivedMessages == true)
            $selectedMessages = $database->getTableRows('messages', [], ['recipient' => $selectedUserID]);
        else
            $selectedMessages = $database->getTableRows('messages', [], ['sender' => $selectedUserID]);
        $availableMessagesLayout = new MetroLayout();
        $availableMessagesListView = new MetroListView('availableMessagesListView', 'Available Messages');
        foreach($selectedMessages as $aSelectedMessage)
        {
            $messageBar = new MetroAppBar('messageBar_' . $aSelectedMessage['code']);
            $onMessageClickProperties = ['username' => $username, 'subject' => $aSelectedMessage['subject'], 'message' => $aSelectedMessage['message'],
            'sent' => $aSelectedMessage['sent'], 'page' => 'app', 'appTitle' => 'Messages', 'action' => 'viewDetailedViewOfMessage',
            'messageID' => $aSelectedMessage['code']];
            if($receivedMessages == true)
            {
                $userTitle = $parentDatabase->getTableRows('people', ['username'], ['code' => $aSelectedMessage['sender']]);
                $onMessageClickProperties['back']['properties'] = ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages',
                'action' => 'viewReceivedMessages'];
                $onMessageClickProperties['back']['loadingMessage'] = 'Opening The Received Messages Page';
            }
            else
            {
                $userTitle = $parentDatabase->getTableRows('people', ['username'], ['code' => $aSelectedMessage['recipient']]);
                $onMessageClickProperties['back']['properties'] = ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages',
                'action' => 'viewSentMessages'];
                $onMessageClickProperties['back']['loadingMessage'] = 'Opening The Sent Messages Page';
            }
            $selectedUsername = '';
            foreach($userTitle as $anUserTitle)
                foreach($anUserTitle as $currentUserTitle)
                    $selectedUsername = $currentUserTitle;
            $onMessageClickProperties['otherParty'] = $selectedUsername;
            $messageBar->addMenuItem($selectedUsername, MetroIcon::user);
            $messageBar->addMenuItem($aSelectedMessage['subject'], MetroIcon::textFile);
            $messageBar->addMenuItem($aSelectedMessage['sent'], MetroIcon::calendar);
            $messageBar->addMenuItem($aSelectedMessage['opened'], MetroIcon::calendar);
            $availableMessagesListView->addListItem($selectedUsername, $aSelectedMessage['subject'], $aSelectedMessage['sent'],
            $this->hide('mainMenuMessagesUpdatePanel') . $this->show('detailedMessageUpdatePanel') .
            $this->updateContent('Opening The Selected Message', $onMessageClickProperties, 'detailedMessageUpdatePanel'));

            /*$availableMessagesLayout->addRow();
            $availableMessagesLayout->addControlToRow($messageBar, 12);
            for($counter = 0; $counter < 5; $counter++)
                $availableMessagesLayout->addEmptyRow();*/

        }
        $availableMessagesLayout->addRow();
        $availableMessagesLayout->addControlToRow($availableMessagesListView, 12);
        if($receivedMessages == true)
        {
            $receivedMessagesAccordion = new MetroAccordion('receivedMessagesAccordion');
            if (is_bool($selectedMessages) && $selectedMessages == false)
                $receivedMessagesAccordion->addItemAsControl('No Received Messages', new MetroHeading('There are currently no messages'), MetroIcon::mail);
            else
                $receivedMessagesAccordion->addItemAsControl('Received Messages', $availableMessagesLayout, MetroIcon::mail);
            return $receivedMessagesAccordion;
        }
        else
        {
            $sentMessagesAccordion = new MetroAccordion('sentMessagesAccordion');
            if(is_bool($selectedMessages) && $selectedMessages == false)
                $sentMessagesAccordion->addItemAsControl('No Sent Messages', new MetroHeading('There are currently no messages'), MetroIcon::mail);
            else
                $sentMessagesAccordion->addItemAsControl('Sent Messages', $availableMessagesLayout, MetroIcon::mail);
            return $sentMessagesAccordion;
        }
    }
    function displayDetailedMessage($subject, $messageContent, $otherPerson, $username, $sent, $backButtonOnClickEvent)
    {
        $detailedMessageViewLayout = $this->createTileGroup('detailedMessageView', ['Back', $subject, $otherPerson],
        [MetroIcon::arrowLeft, MetroIcon::textFile, MetroIcon::user], [$this->hide('detailedMessageUpdatePanel') . $this->show('mainMenuMessagesUpdatePanel')]);
        //$descriptionMessageAccordion = new MetroAccordion('descriptionMessageAccordion');
        //$descriptionMessageAccordion->addItem('Message Sent On ' . $sent, $messageContent, MetroIcon::mail);
        $descriptionMessagePanel = new MetroPanel('descriptionMessagePanel', 'Message Sent On ' . $sent, $messageContent, MetroIcon::mail);
        $detailedMessageViewLayout->addRow();
        $detailedMessageViewLayout->addControlToRow($descriptionMessagePanel, 12);
        $detailedMessageAccordion = new MetroAccordion('detailedMessageAccordion');
        $detailedMessageAccordion->addItemAsControl('Description Of Message', $detailedMessageViewLayout, MetroIcon::mail);
        return $detailedMessageAccordion;
    }
    function sendMessage($username)
    {
        $sendMessageLayout = new MetroLayout();
        $sendMessageAccordion = new MetroAccordion('sendMessageAccordion');
        $recipient = new MetroTextField('recipient', 'Please enter the username of the recipient', 'Please enter the username of the recipient',
        'The username of the recipient goes here');
        $subject = new MetroTextField('subject', 'Please enter the subject of the message', 'Please enter the subject of the message',
        'The subject of the message goes here');
        $description = new MetroTextField('description', 'Please enter the description of the message', 'Please enter the description of the message',
        'The description of the message goes here');
        $sendMessageAccordion->addItemAsControl('Recipient', $recipient, MetroIcon::user);
        $sendMessageAccordion->addItemAsControl('Subject', $subject, MetroIcon::mail);
        $sendMessageAccordion->addItemAsControl('Description', $description, MetroIcon::mail);
        $sendMessageButton = new MetroCommandButton('sendMessageButton', 'Send', 'Send A Message', MetroIcon::mail,
        $this->updateContent('Sending Your Message', ['username' => $username, 'page' => 'app', 'appTitle' => 'Messages', 'action' => 'sendMessage'],
        'messagesUpdatePanel', 'services.php', ['recipient', 'subject', 'description']));
        $cancelMessageButton = new MetroCommandButton('cancelMessageButton', 'Cancel', 'Cancel Your Message', MetroIcon::exit, '', MetroCommandButtonState::danger);
        $sendMessageLayout->addRow();
        $sendMessageLayout->addControlToRow($sendMessageAccordion, 12);
        $sendMessageLayout->addRow();
        $sendMessageLayout->addControlToRow($sendMessageButton, 4, 1, 1);
        $sendMessageLayout->addControlToRow($cancelMessageButton, 4, 1, 1);
        return $sendMessageLayout;
    }
    function processMessage($username, $recipient, $subject, $description)
    {
        $responseArray = [];
        if(strlen($recipient) < 2)
            $responseArray['error'] = ['title' => 'Invalid Recipient', 'content' => 'A valid recipient should have at least 2 characters', 'control' => 'recipient'];
        else if(strlen($subject) < 2)
            $responseArray['error'] = ['title' => 'Invalid Subject', 'content' => 'A valid subject should have at least 2 characters', 'control' => 'subject'];
        else if(strlen($description) < 2)
            $responseArray['error'] = ['title' => 'Invalid Description', 'content' => 'A valid description should have at least 2 characters',
            'control' => 'description'];
        else
        {
            $database = new Database('sample');
            $userDetails = $database->getTableRows('people', ['code'], ['username' => $recipient]);
            if($userDetails->num_rows == 0)
                $responseArray['error'] = ['title' => 'Invalid Recipient', 'content' => $recipient . ' is not a valid recipient', 'control' => 'recipient'];
            else
            {
                $senderCode = '';
                $recipientCode = '';
                $senderDetails = $database->getTableRows('people', ['code'], ['username' => $username]);
                foreach($senderDetails as $aSenderDetails)
                    foreach($aSenderDetails as $aSenderDetail)
                        $senderCode = $aSenderDetail;
                foreach($userDetails as $anUserDetails)
                    foreach($anUserDetails as $anUserDetail)
                        $recipientCode = $anUserDetail;
                $messagesDatabase = new Database('messages');
                $latestMessageIdentifier = $messagesDatabase->getMaxValueOfColumn('messages', 'code');
                $dateTime = new DateTime('GMT');
                $currentDateTime = $dateTime->format('y/m/d');
                $messagesDatabase->insertTableRow('messages', [$latestMessageIdentifier + 1, $recipientCode, $senderCode, $subject, $description,
                $currentDateTime, false]);
                $responseArray['success'] = ['title' => 'Message Sent', 'content' => 'Your message has been successfully sent'];
                $messageContentLayout = $this->createTileGroup('messageAttribute', [$recipient, $subject, $currentDateTime],
                [MetroIcon::user, MetroIcon::textFile, MetroIcon::calendar]);
                $messageContentLayout->addRow();
                $messageContentLayout->addControlToRow(new MetroPanel('messageDescription', 'Description Of The Message', $description, MetroIcon::mail), 12);
                $messageContentAccordion = new MetroAccordion('messageContentAccordion');
                $messageContentAccordion->addItemAsControl('Content Of The Message', $messageContentLayout, MetroIcon::mail);
                $responseArray['html'] = $messageContentAccordion->HTML();
            }
        }
        return $responseArray;
    }
}