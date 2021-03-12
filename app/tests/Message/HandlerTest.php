<?php

declare(strict_types=1);

namespace App\Tests\Message;

use App\Entity\Notification;
use App\Message\Handler;
use App\Message\SendEmailRequest;
use App\MessageBus\SendEmailNotification;
use App\Tests\ContactMother;
use App\Tests\UserMother;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class HandlerTest extends TestCase
{
    /**
     * @var Handler
     */
    private $handler;

    /**
     * @var MockObject&MessageBusInterface
     */
    private $messageBusMock;

    /**
     * @var EntityManagerInterface&MockObject
     */
    private $entityManagerMock;


    public function setUp(): void
    {
        $this->messageBusMock = $this->getMockBuilder(MessageBusInterface::class)->getMock();
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)->getMock();

        $this->handler = new Handler($this->messageBusMock, $this->entityManagerMock);
    }

    public function test_if_handler_persists_and_dispatches_send_email_request_to_message_bus(): void
    {
        $user = UserMother::plainUser();
        $user->addContact(ContactMother::withUser($user));
        $notification = new Notification();

        $content = 'Example Test Content';

        $this->entityManagerMock->expects($this->once())
            ->method('persist');

        $this->messageBusMock->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope(new SendEmailNotification($notification->getId()->toString())));


        $this->handler->handle(new SendEmailRequest($user, $content));
    }
}