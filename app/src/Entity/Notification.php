<?php

declare(strict_types=1);

namespace App\Entity;

use App\Message\MessageStatus;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Notification
{
    public const STATUS_NEW = 'new';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    private UuidInterface $id;

    private string $status;

    private string $content;

    private ?Contact $contact;

    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->status = MessageStatus::NEW;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): void
    {
        $this->contact = $contact;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
