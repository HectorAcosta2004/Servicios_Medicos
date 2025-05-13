<?php
require_once 'Command.php';

class DeleteUserCommand implements Command {
    private $receiver;
    private $userId;

    public function __construct(UserReceiver $receiver, $userId) {
        $this->receiver = $receiver;
        $this->userId = $userId;
    }

    public function execute() {
        $this->receiver->deleteUser($this->userId);
    }
}
?>