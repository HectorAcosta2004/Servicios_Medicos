<?php
require_once 'message_component.php';

class BasicMessage implements MessageComponent {
    private string $message;

    public function __construct(string $message) {
        $this->message = $message;
    }

    public function render(): string {
        return $this->message;
    }
}
