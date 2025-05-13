<?php
require_once 'message_decorator.php';

class AlertDecorator extends MessageDecorator {
    private string $icon;
    private string $title;

    public function __construct(MessageComponent $component, string $icon = 'info', string $title = 'Mensaje') {
        parent::__construct($component);
        $this->icon = $icon;
        $this->title = $title;
    }

    public function render(): string {
        // Usamos json_encode para escapar el texto
        $text = json_encode($this->component->render());
        $title = json_encode($this->title);

        return "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '{$this->icon}',
                    title: {$title},
                    text: {$text},
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = 'index.php'; 
                });
            });
        </script>
        ";
    }
}