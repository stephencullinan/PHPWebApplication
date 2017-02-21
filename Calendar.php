<?php

class Calendar extends Base
{
    function getMainMenu($username)
    {
        $calendarLayout = new MetroLayout();
        $calendarLayout->addRow();
        $calendarLayout->addControlToRow(new MetroCalendar('calendar'), 6, 3, 3);
        $calendarLayout->addRow();
        //$currentDate = new DateTime();
        //$currentDate->setTimezone(new DateTimeZone('GMT'));
        $calendarLayout->addControlToRow(new MetroUpdatePanel('calendarPanel', 'Opening The Calendar', $this->createStreamer($username, new DateTime())), 12);
        return $calendarLayout;
    }
    function createStreamer($username, $startDate)
    {
        $streamerLayout = new MetroLayout();
        $streamerLayout->addRow();
        $appointmentTouchEvents = [];
        $tileColours = [];
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        /*for($index = 0; $index < count($times) - 1; $index++)
        {
            for ($counter = 0; $counter < count($days); $counter++)
            {
                array_push($appointmentTouchEvents, $this->updateContent('Creating The Appointment', ['page' => 'app', 'app' => 'Calendar',
                'username' => $username, 'action' => 'createAppointment', 'day' => $days[$counter], 'date' => $startDate, 'startTime' => $times[$index],
                'endTime' => $times[$index + 1]], 'selectedAppointmentPanel'));
                array_push($tileColours, MetroColour::cyan);
            }
        }*/
        //echo 'APPOINTMENT TOUCH EVENTS: ' . var_dump($appointmentTouchEvents);
        $streamerLayout->addControlToRow(new MetroStreamer(), 12);
        //echo 'SUCCESSFULLY ADDED STREAMER TO LAYOUT';
        //$streamerLayout->addControlToRow(new MetroStreamer(), 12);
        $streamerLayout->addRow();
        $streamerLayout->addControlToRow(new MetroUpdatePanel('selectedAppointmentPanel', 'Opening The Selected Appointment'), 12);
        return $streamerLayout;
    }
    function addNewAppointment($username, $date, $startTime, $endTime)
    {
        $addNewAppointmentLayout = new MetroLayout();
        $addNewAppointmentLayout->addRow();
        $addNewAppointmentLayout->addControlToRow($this->createTileGroup('addNewAppointment', [$date, $startTime, $endTime],
        [MetroIcon::calendar, MetroIcon::alarm, MetroIcon::alarm], []), 12);
        $addNewAppointmentLayout->addRow();
        $addNewAppointmentLayout->addControlToRow($this->createTextBox('title', 'Please enter the title of the appointment'), 12);
        $addNewAppointmentLayout->addRow();
        $addNewAppointmentLayout->addControlToRow($this->createTextBox('subtitle', 'Please enter the subtitle of the appointment'), 12);
        $addNewAppointmentLayout->addRow();
        $addNewAppointmentLayout->addControlToRow($this->createTextBox('description', 'Please enter the description of the appointment'), 12);
        $addNewAppointmentLayout->addRow();
        $addNewAppointmentLayout->addControlToRow(new MetroCommandButton('submitAppointment', 'Save', 'Save Your Appointment', MetroIcon::floppyDisk,
        $this->updateContent('Saving Your Appointment', ['page' => 'app', 'app' => 'Calendar', 'username' => $username, 'action' => 'saveNewAppointment',
        'date' => $date, 'startTime' => $startTime, 'endTime' => $endTime], 'selectedAppointmentPanel', 'services.php', ['title', 'subtitle', 'description'])),
         4, 1, 1);
        $addNewAppointmentLayout->addControlToRow(new MetroCommandButton('cancelAppointment', 'Cancel', 'Cancel This Appointment', MetroIcon::exit, '',
        MetroCommandButtonState::danger), 4, 1, 1);
        return $addNewAppointmentLayout;
    }
    function saveNewAppointment($username, $date, $startTime, $endTime, $title, $subTitle, $description)
    {
        $responseArray = [];
        if(strlen($title) == 0)
            $responseArray['error'] = ['title' => 'Invalid Title', 'content' => 'The Title Of The Appointment Should Be At Least 1 Character Long',
            'control' => 'title'];
        else if(strlen($subTitle) == 0)
            $responseArray['error'] = ['title' => 'Invalid Sub Title', 'content' => 'The Sub Title Of The Appointment Should Be At Least 1 Character Long',
            'control' => 'subtitle'];
        else if(strlen($description) == 0)
            $responseArray['error'] = ['title' => 'Invalid Description', 'content' => 'The Description Of The Appointment Should Be At Least 1 Character Long',
            'control' => 'description'];
        else
        {
            $userID = $this->getUserID($username);
            $calendarDatabase = new Database('calendar');
            $uniqueIdentifier = $calendarDatabase->getMaxValueOfColumn('appointments', 'code');
            $calendarDatabase->insertTableRow('appointments', [$uniqueIdentifier + 1, $date, $startTime, $endTime, $title, $subTitle, $description, $userID]);
            $responseArray['success'] = ['title' => 'Appointment Saved', 'content' => 'Your Appointment Has Been Saved Successfully'];
            $responseArray['html'] = $this->createConfirmationMessage('Your Appointment Has Been Saved Successfully');
        }
        return $responseArray;
    }
    function deleteAppointment($username, $appointmentIdentifier)
    {
        $responseArray = [];
        if(strlen($appointmentIdentifier) == 0)
            $responseArray['error'] = ['title' => 'Invalid Appointment', 'content' => 'The Appointment Identifier Should Contain At Least 1 Character'];
        else
        {
            $calendarDatabase = new Database('calendar');
            $calendarDatabase->removeTableRow('appointments', ['code' => $appointmentIdentifier]);
            $responseArray['success'] = ['title' => 'Appointment Deleted', 'content' => 'Your Appointment Has Been Deleted Successfully'];
        }
        return $responseArray;
    }
}