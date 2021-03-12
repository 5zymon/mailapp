<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\User;

final class SendEmailRequest
{
    public function __construct(
        private User $user,
        private string $content
    ) {
    }

    public function getRecipient(): User
    {
        return $this->user;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
