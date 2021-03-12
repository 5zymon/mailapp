<?php

declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Mime\Email;

class EmailMessageFactory
{
    private const MESSAGE_SUBJECT = 'Message from contact form';
    private const MESSAGE_SENDER = 'somesender@example.com';

    private function __construct()
    {
    }

    public static function create(string $recipient, string $content): Email
    {
        return (new Email())
            ->to($recipient)
            ->from(self::MESSAGE_SENDER)
            ->subject(self::MESSAGE_SUBJECT)
            ->text($content);
    }
}
