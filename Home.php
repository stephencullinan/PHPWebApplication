<?php

class Home extends Base
{
    function getHomePage($username)
    {
        $homePageApps = new MetroWizard('homePageApps');
        $database = new Database('sample');
        $formattedTiles = [];
        $availableApps = $database->getTableRows('apps', ['title'], [], 'title');
        foreach($availableApps as $anAvailableApp)
        {
            foreach ($anAvailableApp as $anAvailableAppProperty)
            {
                $individualTileLayout = new MetroLayout();
                $individualTileLayout->addRow();
                $individualTileLayout->addControlToRow(new MetroIconFont(MetroIcon::apps, MetroIconSize::four), 2, 5, 5);
                $individualTileLayout->addRow();
                $individualTileLayout->addControlToRow(new MetroHeading($anAvailableAppProperty), 12);
                array_push($formattedTiles, new MetroTile('appTile_' . $anAvailableAppProperty, MetroTileSize::square, $individualTileLayout,
                $this->updateContent('Opening The App Titled ' . $anAvailableAppProperty, ['page' => 'app', 'appTitle' => $anAvailableAppProperty,
                'action' => 'openSpecifiedApp', 'username' => $username])));
            }
        }
        $formattedTilesLayout = new MetroLayout();
        for($counter = 0; $counter < count($formattedTiles); $counter = $counter + 3)
        {
            $formattedTilesLayout->addRow();
            $formattedTilesLayout->addControlToRow($formattedTiles[$counter], 3, 1, 0);
            if($counter + 1 < count($formattedTiles))
                $formattedTilesLayout->addControlToRow($formattedTiles[$counter + 1], 3, 1, 0);
            if($counter + 2 < count($formattedTiles))
                $formattedTilesLayout->addControlToRow($formattedTiles[$counter + 2], 3, 1, 0);
        }
        $availableAppsAccordion = new MetroAccordion('availableAppsAccordion');
        $availableAppsAccordion->addItemAsControl('Available Apps', $formattedTilesLayout, MetroIcon::apps);
        $homePageApps->addStep($availableAppsAccordion);
        return $homePageApps;
    }
}