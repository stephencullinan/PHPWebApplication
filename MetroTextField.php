<?php

class MetroTextField extends MetroComponent
{
    function __construct($id, $titleText, $hintText, $placeHolderText, $enum_MetroIcon = MetroIcon::pencil, $password = false, $initialValue = '')
    {
        parent::__construct();
        if($password == false)
        {
            $this->addElement('div', $initialValue, [], ['class' => 'input-control modern text iconic full-size', 'id' => $id, 'data-role' => 'input'
            /*, 'style' => 'width:100%;'*/]);
            $this->addElement('input', $initialValue, [0], ['type' => 'text', 'id' => $id . '_Input']);
        }
        else
        {
            $this->addElement('div', $initialValue, [], ['class' => 'input-control modern password iconic full-size', 'id' => $id, 'data-role' => 'input'
            /*, 'style' => 'width:100%;'*/]);
            $this->addElement('input', $initialValue, [0], ['type' => 'password', 'id' => $id . '_Input']);
        }
        $this->addElement('span', $titleText, [0], ['class' => 'label']);
        $this->addElement('span', $hintText, [0], ['class' => 'informer']);
        $this->addElement('span', $placeHolderText, [0], ['class' => 'placeholder']);
        $this->addElement('span', '', [0], ['class' => 'icon mif-' . $enum_MetroIcon]);
        if($password == true)
            $this->addElement('button', '', [0], ['class' => 'button helper-button reveal']);
        else
            $this->addElement('button', '', [0], ['class' => 'button helper-button clear']);
        $errorPopover = new MetroPopover($id . '_Error', '', MetroColour::red, MetroColour::white, MetroPopoverPosition::top, false);
        $errorPopoverLayout = new MetroLayout();
        $errorPopoverLayout->addRow();
        $errorPopoverLayout->addControlToRow($errorPopover, 8, 2, 2);
        $this->addControl($errorPopoverLayout);
    }
}
/*
<div class="input-control modern text iconic">
    <input type="text">
    <span class="label">You login</span>
    <span class="informer">Please enter you login or email</span>
    <span class="placeholder">Input login</span>
    <span class="icon mif-user"></span>
</div>
<div class="input-control modern password iconic" data-role="input">
    <input type="password">
    <span class="label">You password</span>
    <span class="informer">Please enter you password</span>
    <span class="placeholder">Input password</span>
    <span class="icon mif-lock"></span>
    <button class="button helper-button reveal"><span class="mif-looks"></span></button>
</div>
*/