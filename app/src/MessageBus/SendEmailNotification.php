<?php

declare(strict_types=1);

namespace App\MessageBus;

final class SendEmailNotification
{
    public function __construct(
        private string $notificationId
    ) {
    }

    public function getNotificationId(): string
    {
        return $this->notificationId;
    }
}
