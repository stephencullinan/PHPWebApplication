<?php

class MetroStreamer extends MetroComponent
{
    function __construct($touchEvents = [], $tileColours = [])
    {
        parent::__construct();
        $metroStreamerLayout = new MetroLayout();
        $metroStreamerLayout->addRow();
        $headings = ['Time', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $times = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
        $counter = 0;
        foreach($headings as $aHeading)
        {
            $sideBar = new MetroSideBar('sideBar_' . $counter);
            $counter++;
            $sideBar->addItem($aHeading, 'Day', MetroIcon::calendar);
            //$metroStreamerLayout->addControlToRow(new MetroTile('headingTile_' . $aHeading, MetroTileSize::square, new MetroHeading($aHeading), '', '', false,
            //MetroColour::cyan, MetroColour::white), 2);
            $metroStreamerLayout->addControlToRow($sideBar, 2);
        }
        $numberOfTimes = 0;
        foreach($times as $aTime)
        {
            $metroStreamerLayout->addRow();
            $sideBar = new MetroSideBar('sideBar_' . $aTime);
            $sideBar->addItem($aTime, '', MetroIcon::alarm);
            $metroStreamerLayout->addControlToRow($sideBar, 2);
            //$metroStreamerLayout->addControlToRow(new MetroTile('timeTile_' . $aTime, MetroTileSize::square, new MetroHeading($aTime), '', '', false,
            //MetroColour::cyan, MetroColour::white), 2);
            for($counter = 0; $counter < 5; $counter++)
            {
                /*$base = new Base();
                $selectedTouchEvent =  $base->updateContent('Creating The Appointment', ['page' => 'app', 'app' => 'Calendar',
                'username' => 'username', 'action' => 'createAppointment', 'day' => 'Monday', 'date' => new DateTime(), 'startTime' => '10:00',
                'endTime' => '11:00'], 'selectedAppointmentPanel');
                $selectedTileColour = '';
                if(array_key_exists($numberOfTimes, $touchEvents) == true)
                {
                    $selectedTouchEvent = $touchEvents[$numberOfTimes];
                    echo 'SELECTED TOUCH EVENT: ' . $selectedTouchEvent;
                }
                if(array_key_exists($numberOfTimes, $tileColours) == true)
                    $selectedTileColour = $tileColours[$numberOfTimes];*/

                $sideBar = new MetroSideBar('sideBar_' . $aTime . '_' . $counter, MetroSideBarType::green);

                //$base->updateContent('Creating The Appointment', ['page' => 'app', 'app' => 'Calendar',
                //'username' => 'username', 'action' => 'createAppointment', 'day' => 'Monday', /*'date' => new DateTime(),*/ 'startTime' => '10:00',
                //'endTime' => '11:00'], 'selectedAppointmentPanel'));

                $sideBar->addItem('', '', MetroIcon::calendar);
                $metroStreamerLayout->addControlToRow($sideBar, 2);

                //$metroStreamerLayout->addControlToRow(new MetroTile('appointmentTile_' . $aTime . '_' . $counter, MetroTileSize::square, new MetroHeading(""),
                //$selectedTouchEvent, '', false, $selectedTileColour, MetroColour::white), 2);

                $numberOfTimes++;
            }
        }
        $this->addControl($metroStreamerLayout);
    }
}