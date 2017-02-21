<?php

class MetroCommandButton extends MetroComponent
{
    function __construct($id, $titleText, $descriptionText, $enum_MetroIcon = MetroIcon::Chrome, $onClickEvent = '', $enum_MetroCommandButtonState = MetroCommandButtonState::success)
    {
        parent::__construct();
        $this->addElement('button', $titleText, [], ['class' => 'command-button ' . $enum_MetroCommandButtonState, 'onclick' => $onClickEvent,
        'style' => 'width:100%;']);
        $this->addElement('span', '', [0], ['class' => 'icon mif-' . $enum_MetroIcon]);
        $this->addElement('small', $descriptionText, [0]);
    }
}
/*


    <button class="command-button">
        <span class="icon mif-share"></span>
        Yes, share and connect
        <small>Use this option for home or work</small>
    </button>
    <button class="command-button icon-right warning">
        <span class="icon mif-share"></span>
        Yes, share and connect
        <small>Use this option for home or work</small>
    </button>


*/