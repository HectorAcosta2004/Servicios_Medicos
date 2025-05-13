<?php
require_once 'message_component.php';

abstract class MessageDecorator implements MessageComponent {
    protected MessageComponent $component;

    public function __construct(MessageComponent $component) {
        $this->component = $component;
    }
}
