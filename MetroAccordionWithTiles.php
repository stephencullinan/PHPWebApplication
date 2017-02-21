<?php

class MetroAccordionWithTiles extends MetroAccordion
{
    private $id;
    private $tileLayout;
    private $tileNumber;
    private $numberOfTiles;
    private $tileWidth;
    private $leftWidth;
    private $rightWidth;
    function __construct($id, $numberOfTiles, $tileWidth = 3, $leftWidth = 0, $rightWidth = 0)
    {
        parent::__construct($id);
        $this->id = $id;
        $this->tileLayout = new MetroLayout();
        $this->tileLayout->addRow();
        $this->tileNumber = 0;
        $this->numberOfTiles = $numberOfTiles;
        $this->tileWidth = $tileWidth;
        $this->leftWidth = $leftWidth;
        $this->rightWidth = $rightWidth;
    }
    function addTile($headingText, $enum_MetroIcon, $onClickEvent = '', $tileSelected = false)
    {
        $this->tileNumber++;
        $individualTileLayout = new MetroLayout();
        $individualTileLayout->addRow();
        $individualTileLayout->addControlToRow(new MetroHeading($headingText), 12);
        $individualTileLayout->addRow();
        $individualTileLayout->addControlToRow(new MetroIconFont($enum_MetroIcon, MetroIconSize::four), 2, 5, 5);
        $this->tileLayout->addControlToRow(new MetroTile($this->id . '_Tile_' . $this->tileNumber, MetroTileSize::wide, $individualTileLayout,
        'edit("' . $this->id . '_0", "' . $headingText . '<span class=\"mif-' . $enum_MetroIcon . ' icon\"></span>");editClassNameForMultipleElements("' . $this->id .
        '_Tile", "tile-wide bg-cyan fg-white", ' . $this->numberOfTiles . ');appendClassName("' . $this->id . '_Tile_' .
        $this->tileNumber . '", " element-selected");' . $onClickEvent, '', $tileSelected),
        $this->tileWidth, $this->leftWidth, $this->rightWidth);
    }
    function addTileLayout($headingText, $enum_MetroIcon)
    {
        $this->addItemAsControl($headingText, $this->tileLayout, $enum_MetroIcon);
    }
}