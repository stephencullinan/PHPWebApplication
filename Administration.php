<?php

class Administration extends Base
{
    function __construct()
    {
        parent::__construct();
    }
    function viewTableProperties($tableTitle)
    {
        $database = new Database('mysql');
        $tablePropertiesLayout = new MetroLayout();
        $tableColumnTitles = $database->getTableRowTitles($tableTitle);
        for($counter = 0; $counter < count($tableColumnTitles); $counter = $counter + 3)
        {
            $tablePropertiesLayout->addRow();
            for($index = $counter; $index < $counter + 3 && $index < count($tableColumnTitles); $index++)
            {
                $tileLayout = new MetroLayout();
                $tileLayout->addRow();
                $tileLayout->addControlToRow(new MetroHeading($tableColumnTitles[$index], '', MetroHeadingSize::Two), 12);
                $tileLayout->addRow();
                $tileLayout->addControlToRow(new MetroIconFont(MetroIcon::textFile, MetroIconSize::four), 2, 5, 5);
                $tablePropertiesLayout->addControlToRow(new MetroTile('TableProperty_' . $index, MetroTileSize::square, $tileLayout, ''), 3, 1, 0);
            }
        }
        $tablePropertiesAccordion = new MetroAccordion('tablePropertiesAccordion');
        $tablePropertiesAccordion->addItemAsControl('Properties of ' . $tableTitle, $tablePropertiesLayout, MetroIcon::info);
        return $tablePropertiesAccordion;
    }
    function addRow($tableTitle, $rowNumber, $totalNumberOfRows)
    {
        $database = new Database('mysql');
        $tableColumnTitles = $database->getTableRowTitles($tableTitle);
        $addRowLayout = new MetroLayout();
        $addRowProgressBar = new MetroProgressBar('addRowProgressBar', ($rowNumber / $totalNumberOfRows) * 100);
        $addRowHeading = new MetroHeading('Row ' . $rowNumber . ' Of ' . $totalNumberOfRows);
        $addRowAccordion = new MetroAccordion('addRowAccordion');
        foreach($tableColumnTitles as $aTableColumnTitle)
        {
            $addRowAccordion->addItemAsControl($aTableColumnTitle, new MetroTextField('TextField_' . $aTableColumnTitle, 'Please enter a value for ' .
            $aTableColumnTitle, 'Please enter a value for ' . $aTableColumnTitle, 'Your value for ' . $aTableColumnTitle . ' goes here'), MetroIcon::pencil);
        }
        $addRowSubmitButton = new MetroCommandButton('addRowSubmitButton', 'Add Row', 'Insert New Row', MetroIcon::checkmark, '', MetroCommandButtonState::success);
        $addRowCancelButton = new MetroCommandButton('addRowCancelButton', 'Cancel', 'Cancel New Row', MetroIcon::exit, '', MetroCommandButtonState::danger);
        $addRowLayout->addRow();
        $addRowLayout->addControlToRow($addRowHeading, 12);
        $addRowLayout->addRow();
        $addRowLayout->addControlToRow($addRowProgressBar, 12);
        $addRowLayout->addRow();
        $addRowLayout->addControlToRow($addRowAccordion, 12);
        $addRowLayout->addRow();
        $addRowLayout->addControlToRow($addRowSubmitButton, 4, 1, 1);
        $addRowLayout->addControlToRow($addRowCancelButton, 4, 1, 1);
        return $addRowLayout;
    }
    function addMultipleRows($tableName)
    {
        $addMultipleRowsAccordion = new MetroAccordion('addMultipleRowsAccordion');
        $addMultipleRowsLayout = new MetroLayout();
        $addMultipleRowsLayout->addRow();
        $addMultipleListItems = new MetroListView('addMultipleListItems', 'Select the number of rows to be created');
        $addMultipleListItems->addListItem(1 . ' Row', 'Add ' . 1 . ' Row', $tableName, $this->updateContent('Opening The Add New Row Page', ['page' => 'administration', 'action' => 'addNewRow', 'table' => $tableName, 'currentRowNumber' => 1, 'totalRowNumber' => 1], 'administrationUpdatePanel'));
        for($counter = 2; $counter <= 10; $counter++)
        {
            $addMultipleListItems->addListItem($counter . ' Rows', 'Add ' . $counter . ' Rows', $tableName, $this->updateContent('Opening The Add New Row Page', ['page' => 'administration', 'action' => 'addNewRow', 'table' => $tableName, 'currentRowNumber' => 1, 'totalRowNumber' => $counter], 'administrationUpdatePanel'));
        }
        $addMultipleRowsLayout->addControlToRow($addMultipleListItems, 8, 2, 2);
        $addMultipleRowsAccordion->addItemAsControl('Add Multiple Rows To ' . $tableName, $addMultipleListItems, MetroIcon::plus);
        return $addMultipleRowsAccordion;
    }
    function openSpecifiedTable($table)
    {
        $database = new Database('mysql');
        $tableContents = $database->getTableRows($table);
        $tableColumnTitles = $database->getTableRowTitles($table);
        $tableContentsLayout = new MetroLayout();
        $tableContentsProgressBar = new MetroProgressBar('tableContentsProgressBar', 25);
        $tableContentsStepper = new MetroStepper('tableContentsStepper', 4);
        $tableContentsWizard = new MetroWizard('tableContentsWizard');
        if(count($tableContents) > 0)
        {
            $tableContentsLayout->addRow();
            $tableContentsLayout->addControlToRow($tableContentsProgressBar, 12);
            $tableContentsLayout->addRow();
            $tableContentsLayout->addControlToRow($tableContentsStepper, 12);
            $tableContentsLayout->addRow();
            $resultsRowsLayout = new MetroLayout();
            $counter = 0;
            foreach ($tableContents as $aTableContent)
            {
                $rowLayout = new MetroLayout();
                $rowLayout->addRow();
                foreach ($aTableContent as $aCell)
                {
                    $cellLayout = new MetroLayout();
                    $cellLayout->addRow();
                    $cellLayout->addControlToRow(new MetroHeading($aCell, '', MetroHeadingSize::Two), 12);
                    $rowLayout->addControlToRow(new MetroTile('', MetroTileSize::square, $cellLayout), 3);
                }
                $resultsRowsLayout->addRow();
                $resultsRowsLayout->addControlToRow($rowLayout, 12);
                $counter++;
                if ($counter == 10)
                    break;
            }
            $tableContentsWizard->addStep($resultsRowsLayout);
            for ($counter = 10; $counter < $tableContents->num_rows; $counter = $counter + 10)
                $tableContentsWizard->addStep(new MetroUpdatePanel('tableContents_Start_' . $counter, 'Loading The Selected Content'));
            $tableContentsLayout->addControlToRow($tableContentsWizard, 12);
        }
        /*for($counter = 0; $counter < 25; $counter = $counter + 5)
        {
            $resultsPagesLayout = new MetroLayout();
            for($index = 0; $index < 5; $index++)
            {
                $resultsPagesLayout->addRow();
                $resultsPagesLayout->addControlToRow($resultsRows[$counter + $index], 12);
            }
            $tableContentsWizard->addStep($resultsPagesLayout);
        }*/
        return $tableContentsLayout;
    }
    function createAdministrationMenu($tableTitle = '')
    {
        $administrationMenu = new MetroFluentMenu('administrationMenu');
        $administrationMenu->addTab('Available Tables');
        $database = new Database('mysql');
        if($_SESSION['tableTitles'] == null)
           $_SESSION['tableTitles'] = $database->getTablesFromDatabase();
        $largeButtons = [];
        $tableTitles = $_SESSION['tableTitles'];
        foreach($tableTitles as $aTableTitle)
            array_push($largeButtons, new MetroFluentBigButton('Table_' . $aTableTitle, $this->updateContent('Opening The Table ' . $aTableTitle, ['page' => 'administration', 'action' => 'openTable', 'table' => $aTableTitle], 'administrationUpdatePanel') .
            $this->updateContent('', ['page' => 'administration', 'action' => 'updateMenu', 'table' => $aTableTitle], 'administrationMenuUpdatePanel'),
            MetroIcon::database, $aTableTitle));
        $administrationMenu->addTabContent(0, $largeButtons, 'Available Tables');
        if(strlen($tableTitle) > 0)
        {
            $administrationMenu->addTab($tableTitle);
            $largeButtons = [];
            array_push($largeButtons, new MetroFluentBigButton('Table_' . $tableTitle . '_Properties', $this->updateContent('Opening The Table Properties Page', ['page' => 'administration', 'action' => 'viewTableProperties', 'table' => $tableTitle], 'administrationUpdatePanel'),
            MetroIcon::info, 'Table Properties'));
            array_push($largeButtons, new MetroFluentBigButton('Table_' . $tableTitle . '_AddRow', $this->updateContent('Opening The Add New Row Page', ['page' => 'administration', 'action' => 'addNewRow', 'table' => $tableTitle, 'currentRowNumber' => 1, 'totalRowNumber' => 1], 'administrationUpdatePanel'), MetroIcon::plus, 'Add Row'));
            array_push($largeButtons, new MetroFluentBigButton('Table_' . $tableTitle . '_AddMultipleRows', $this->updateContent('Opening The Add Multiple Rows Page', ['page' => 'administration', 'action' => 'addMultipleRows', 'table' => $tableTitle], 'administrationUpdatePanel'), MetroIcon::plus, 'Add Multiple Rows'));
            $administrationMenu->addTabContent(1, $largeButtons, 'Table Properties');
        }
        return $administrationMenu;
    }
    function getAdministrationPage()
    {
        $administrationPageLayout = new MetroLayout();
        /*$administrationMenu = new MetroFluentMenu('administrationMenu');
        $administrationMenu->addTab('Available Tables');
        $database = new Database('mysql');
        $tableTitles = $database->getTablesFromDatabase();
        $_SESSION['tableTitles'] = $tableTitles;
        $largeButtons = [];
        foreach($tableTitles as $aTableTitle)
            array_push($largeButtons, new MetroFluentBigButton('Table_' . $aTableTitle, $this->updateContent('Opening The Table ' . $aTableTitle,
            ['page' => 'administration', 'action' => 'openTable', 'table' => $aTableTitle], 'administrationUpdatePanel'), MetroIcon::database, $aTableTitle));
        $administrationMenu->addTabContent(0, $largeButtons, 'Available Tables');*/
        $administrationMenu = $this->createAdministrationMenu();
        $administrationPageLayout->addRow();
        //$administrationPageLayout->addControlToRow(new MetroUpdatePanel('administrationMenuUpdatePanel', '', $administrationMenu), 12);
        $administrationPageLayout->addControlToRow(new MetroUpdatePanelWithNoLoadingPanel('administrationMenuUpdatePanel', $administrationMenu), 12);
        $administrationPageLayout->addRow();
        $administrationPageLayout->addControlToRow(new MetroUpdatePanel('administrationUpdatePanel', 'Retrieving Your Desired Content'), 12);
        return $administrationPageLayout;
        //$columnTitles = $database->getTableRowTitles('HELP_CATEGORY');
        /*$availableData = $database->getTableRows('HELP_CATEGORY');
        $resultsCarousel = new MetroCarousel('resultsCarousel');
        $resultsRows = [];
        foreach($availableData as $aRow)
        {
            $rowLayout = new MetroLayout();
            $rowLayout->addRow();
            foreach ($aRow as $aCell)
            {
                $cellLayout = new MetroLayout();
                $cellLayout->addRow();
                $cellLayout->addControlToRow(new MetroHeading($aCell, '', MetroHeadingSize::Six), 12);
                $rowLayout->addControlToRow(new MetroTile('', MetroTileSize::square, $cellLayout), 3);
            }
            array_push($resultsRows, $rowLayout);
        }
        $resultsPages = [];
        for($counter = 0; $counter < 5; $counter = $counter + 5)
        {
            $resultsPagesLayout = new MetroLayout();
            for($index = 0; $index < 5; $index++)
            {
                $resultsPagesLayout->addRow();
                $resultsPagesLayout->addControlToRow($resultsRows[$counter + $index], 12);
            }
            $resultsCarousel->addControlSlide($resultsPagesLayout);
        }*/
        /*$administrationMenuLayout = new MetroLayout();
        $administrationMenuLayout->addRow();
        $administrationMenuLayout->addControlToRow($administrationMenu, 12);
        $administrationMenuLayout->addRow();
        $administrationMenuLayout->addControlToRow($resultsCarousel, 12);
        return $administrationMenuLayout;*/
        /*foreach($resultsRows as $aResultRow)
        {
            $resultsCarousel->addControlSlide($aResultRow);
        }
        $administrationMenuLayout = new MetroLayout();
        $administrationMenuLayout->addRow();
        $administrationMenuLayout->addControlToRow($administrationMenu, 12);
        $administrationMenuLayout->addRow();
        $administrationMenuLayout->addControlToRow($resultsCarousel, 12);
        return $administrationMenuLayout;*/
        /*$pageLayout = new MetroLayout();
        for($counter = 0; $counter < count($resultsRows); $counter++)
        {
            if($counter % 20 == 0 && $counter > 0)
            {
                array_push($resultsPages, $pageLayout);
                $pageLayout = new MetroLayout();
            }
            $pageLayout->addRow();
            $pageLayout->addControlToRow($resultsRows[$counter], 12);
            if($counter == count($resultsRows) - 1)
                array_push($resultsPages, $pageLayout);
        }
        foreach($resultsPages as $aResultPage)
        {
            $resultsCarousel->addControlSlide($aResultPage);
        }
        $administrationMenuLayout = new MetroLayout();
        $administrationMenuLayout->addRow();
        $administrationMenuLayout->addControlToRow($administrationMenu, 12);
        $administrationMenuLayout->addControlToRow($resultsCarousel, 12);
        return $administrationMenuLayout;*/
        //return $resultsCarousel;
    }
    function getRowsOfTable()
    {

    }
}