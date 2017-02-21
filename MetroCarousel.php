<?php

class MetroCarousel extends MetroComponent
{
    function __construct($id, $startSlide = false, $durationOfSlide = 100, $enum_MetroCarouselDirection = MetroCarouselDirection::left,
                         $enum_MetroCarouselEffect = MetroCarouselEffect::slide, $controlsVisible = true, $nextControl = '>', $previousControl = '<',
                         $markersVisible = true, $stopOnMouseOver = true, $width = '100%', $height = false)
    {
        parent::__construct();
        $this->addElement("div", "", [], ["class" => "carousel", "data-role" => "carousel", "id" => $id, 'data-auto' => $startSlide, 'data-duration' => 10000,
        'data-direction' => $enum_MetroCarouselDirection, 'data-effect' => $enum_MetroCarouselEffect, 'data-effect-func' => 'linear', 'data-controls' => $controlsVisible,
        'data-control-next' => $nextControl, 'data-control-prev' => $previousControl, 'data-markers' => $markersVisible, 'data-stop' => $stopOnMouseOver,
         'data-width' => $width, 'data-height' => $height]);
    }
    function addTextualSlide($title, $description)
    {
        $this->addElement("div", "", [0], ["class" => "slide"]);
        $currentSlidePosition = $this->getNumberOfElements([0]);
        $this->addElement('h2', $title, [0, $currentSlidePosition]);
        $this->addElement('p', $description, [0, $currentSlidePosition]);
    }
    function addControlSlide(MetroComponent $control)
    {
        $this->addElement('div', '', [0], ['class' => 'slide']);
        $this->addControl($control, [0, $this->getNumberOfElements([0])]);
    }
}