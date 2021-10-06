<?php

namespace App\Dto;

use Illuminate\Notifications\Messages\BroadcastMessage;

class BroadcastNotificationDto
{
    private string $message;
    private array $actions;

    /**
     * @param string $message
     * @param array $actions
     */
    public function __construct(string $message, array $actions = [])
    {
        $this->message = $message;
        $this->actions = $actions;
    }


    public function __invoke(): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'actions' => $this->actions
        ]);
    }
}
