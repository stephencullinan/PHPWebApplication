<!DOCTYPE html>
<html>
<head>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
    <?php
    function __autoload($classTitle)
    {
        require_once $classTitle . '.php';
    }
    function updateContent($loadingMessage = 'Retrieving Your Content', $parameters = [], $panelTitle = 'titleUpdatePanel', $requestedPage = 'services.php')
    {
        return 'updateContent("' . $panelTitle . '", "' . $requestedPage . '", "' .  $loadingMessage . '", ' . json_encode($parameters) . ')';
    }
    $titleAccordion = new MetroAccordionWithTiles('title', 3, 3, 1, 0);
    $titleAccordion->addTile('Home', MetroIcon::home, updateContent('Opening The Home Page', ['page' => 'administration']));
    $titleAccordion->addTile('Register', MetroIcon::userplus, updateContent('Opening The Registration Page', ['page' => 'registration']));
    $titleAccordion->addTile('Log On', MetroIcon::enter, updateContent('Opening The Log On Page', ['page' => 'logOn']), true);
    $titleAccordion->addTileLayout('Log On', MetroIcon::enter);
    $updatePanel = new MetroUpdatePanel('menuUpdatePanel', 'Updating The Menu', $titleAccordion);
    echo $updatePanel->HTML();
    $logOn = new LogOn();
    $updatePanel = new MetroUpdatePanel('titleUpdatePanel', 'Retrieving Your Content', $logOn->getLogOnPage());
    echo $updatePanel->HTML();
    //$parameters = [];
    //$parameters['action'] = 'logOn';
    /*$revisedTextField = new MetroTextField('revisedTextField', 'Your username goes here', 'Your username goes here', 'Your username goes here');
    echo $revisedTextField->HTML();*/
    /*$titleAccordion = new MetroAccordion("titleAccordion");
    $titleLayout = new MetroLayout();
    $titleLayout->addRow();
    $firstTileLayout = new MetroLayout();
    $firstTileLayout->addRow();
    $firstTileLayout->addControlToRow(new MetroIconFont(MetroIcon::Home, MetroIconSize::two), 12);
    $firstTileLayout->addRow();
    $firstTileLayout->addControlToRow(new MetroHeading('Home'), 12);
    $titleLayout->addControlToRow(new MetroTile('titleAccordion_Tile_1', MetroTileSize::wide, $firstTileLayout,
    'edit("titleAccordion_0", "HOME<span class=\"mif-chrome icon\"></span>");editClassNameForMultipleElements("titleAccordion_Tile", 
    "tile-wide bg-cyan fg-white", 3);appendClassName("titleAccordion_Tile_1", " element-selected");'), 3, 1, 0);
    $titleLayout->addControlToRow(new MetroTile('titleAccordion_Tile_2', MetroTileSize::wide, new MetroHeading('REGISTER'),
    'edit("titleAccordion_0", "REGISTER<span class=\"mif-chrome icon\"></span>");editClassNameForMultipleElements("titleAccordion_Tile", 
    "tile-wide bg-cyan fg-white", 3);appendClassName("titleAccordion_Tile_2", " element-selected");'), 3, 1, 0);
    $titleLayout->addControlToRow(new MetroTile('titleAccordion_Tile_3', MetroTileSize::wide, new MetroHeading('LOG ON'),
    'edit("titleAccordion_0", "LOG ON<span class=\"mif-chrome icon\"></span>");editClassNameForMultipleElements("titleAccordion_Tile", 
    "tile-wide bg-cyan fg-white", 3);appendClassName("titleAccordion_Tile_3", " element-selected");'), 3, 1, 0);
    $titleAccordion->addItemAsControl('HOME', $titleLayout, MetroIcon::Chrome);
    echo $titleAccordion->HTML();*/
    /*
     HOME<span class="mif-chrome icon"></span>
    */
    /*$revisedFluentMenu = new MetroFluentMenu('revisedFluentMenu');
    for($counter = 1; $counter < 7; $counter++)
        $revisedFluentMenu->addTab('Tab ' . $counter);
    for($counter = 0; $counter < 6; $counter++)
        $revisedFluentMenu->addTabContent($counter, new MetroFluentBigButton('revisedFluentBigButton_' . $counter, '', MetroIcon::Chrome, 'Click Here'), 'Click Here');
    echo $revisedFluentMenu->HTML();
    $revisedAppBar = new MetroAppBar('revisedAppBar');
    $revisedAppBar->addMenuItem('Menu Item 1', MetroIcon::Chrome);
    $revisedAppBar->addMenuItem('Menu Item 2', MetroIcon::Chrome);
    $revisedAppBar->addMenuItem('Menu Item 3', MetroIcon::Chrome);
    echo $revisedAppBar->HTML();
    $revisedHeading = new MetroHeading('REVISED HEADING TITLE', 'REVISED HEADING SUB TITLE', MetroHeadingSize::One);
    echo $revisedHeading->HTML();
    $revisedPreLoader = new MetroPreLoader();
    echo $revisedPreLoader->HTML();
    $revisedAccordion = new MetroAccordion('revisedAccordion');
    $revisedAccordion->addItem('Item 1', 'Content For Item 1', MetroIcon::Chrome);
    $revisedAccordion->addItem('Item 2', 'Content For Item 2', MetroIcon::Chrome);
    echo $revisedAccordion->HTML();
    $revisedCarousel = new MetroCarousel('revisedCarousel');
    $revisedCarousel->addTextualSlide('Slide 1', 'Content Of Slide 1');
    $revisedCarousel->addTextualSlide('Slide 2', 'Content Of Slide 2');
    echo $revisedCarousel->HTML();
    $revisedCharm = new MetroCharm('revisedCharm');
    echo $revisedCharm->HTML();
    $revisedPanel = new MetroPanel('revisedPanel', 'REVISED PANEL TITLE', 'This is the content of the revised panel', MetroIcon::Chrome);
    echo $revisedPanel->HTML();
    $revisedCommandButton = new MetroCommandButton('revisedCommandButton', 'Show Charm', 'Click here to show the charm', MetroIcon::Chrome, 'showMetroCharm("revisedCharm", "top");');
    echo $revisedCommandButton->HTML();
    $revisedPopover = new MetroPopover('revisedPopover', 'This is a sample popover');
    echo $revisedPopover->HTML();
    $revisedLayout = new MetroLayout();
    $revisedLayout->addEmptyRow();
    $revisedLayout->addRow();
    $revisedLayout->addControlToRow($revisedPopover, 4, 1, 1);
    $revisedLayout->addControlToRow($revisedPopover, 4, 1, 1);
    echo $revisedLayout->HTML();
    $revisedProgressBar = new MetroProgressBar('revisedProgressBar', 100);
    echo $revisedProgressBar->HTML();
    $revisedSideBar = new MetroSideBar('revisedSideBar');
    $revisedSideBar->addItem('Item 1', 'Description', MetroIcon::Chrome);
    $revisedSideBar->addItem('Item 2', 'Description', MetroIcon::Chrome);
    $revisedSideBar->addItem('Item 3', 'Description', MetroIcon::Chrome);
    $revisedLayout = new MetroLayout();
    $revisedLayout->addRow();
    $revisedLayout->addControlToRow($revisedSideBar, 4, 4, 4);
    echo $revisedLayout->HTML();
    $revisedStepper = new MetroStepper('revisedStepper', 10);
    $revisedLayout = new MetroLayout();
    $revisedLayout->addRow();
    $revisedLayout->addControlToRow($revisedStepper, 10, 1, 1);
    echo $revisedLayout->HTML();
    $revisedTabs = new MetroTabs('revisedTabs');
    for($counter = 1; $counter < 6; $counter++)
        $revisedTabs->addTab('TAB ' . $counter, 'CONTENT OF TAB ' . $counter);
    echo $revisedTabs->HTML();
    $revisedTable = new MetroTable('revisedTable');
    $revisedTable->addTableColumns(['Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5']);
    $tableValues = [];
    for($counter = 0; $counter < 6; $counter++)
        $tableValues[$counter] = ['Value 1', 'Value 2', 'Value 3', 'Value 4', 'Value 5'];
    $revisedTable->addTableRows($tableValues);
    echo $revisedTable->HTML();
    $revisedLayout = new MetroLayout();
    $revisedLayout->addRow();
    $revisedLayout->addControlToRow(new MetroHeading('Title', ''));
    $revisedLayout->addRow();
    $revisedLayout->addControlToRow(new MetroHeading('Description', ''));
    $revisedTile = new MetroTile('revisedTile', MetroTileSize::square, $revisedLayout);
    echo $revisedTile->HTML();*/
    ?>
</body>
</html>