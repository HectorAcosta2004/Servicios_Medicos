<?php
require_once 'message_decorator.php';

class IconDecorator extends MessageDecorator {
    private string $icon;

    public function __construct(MessageComponent $component, string $icon) {
        parent::__construct($component);
        $this->icon = $icon;
    }

    public function render(): string {
        return "🔔 " . $this->component->render();
    }
}
