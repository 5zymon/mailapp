<?php

declare(strict_types=1);

namespace App\Tests\MessageBus;

use App\Entity\Notification;
use App\Message\MessageStatus;
use App\MessageBus\EmailNotificationsHandler;
use App\MessageBus\SendEmailNotification;
use App\Repository\NotificationRepository;
use App\Tests\ContactMother;
use App\Tests\UserMother;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class EmailNotificationHandlerTest extends TestCase
{
    /**
     * @var EmailNotificationsHandler
     */
    private $emailNotificationHandler;

    /**
     * @var MailerInterface&MockObject
     */
    private $mailerMock;

    /**
     * @var NotificationRepository&MockObject
     */
    private $notificationRepositoryMock;

    /**
     * @var EntityManagerInterface&MockObject
     */
    private $entityManagerMock;

    /**
     * @var LoggerInterface&MockObject
     */
    private $loggerMock;


    public function setUp(): void
    {
        $this->mailerMock = $this->getMockBuilder(MailerInterface::class)->getMock();
        $this->notificationRepositoryMock = $this->getMockBuilder(NotificationRepository::class)
            ->disableOriginalConstructor()->getMock();
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->emailNotificationHandler = new EmailNotificationsHandler(
            $this->mailerMock,
            $this->notificationRepositoryMock,
            $this->entityManagerMock,
            $this->loggerMock
        );
    }

    public function test_if_does_not_send_message_when_contact_is_removed_from_notification()
    {
        $notification = new Notification();
        $notification->setContact(null);
        $sendEmailNotification = new SendEmailNotification($notification->getId()->toString());

        $this->notificationRepositoryMock
            ->method('findOneBy')
            ->willReturn($notification);

        $this->mailerMock->expects(self::never())->method('send');

        $this->emailNotificationHandler->__invoke($sendEmailNotification);
    }

    public function test_if_will_update_status_to_sent_after_successful_send()
    {
        $notification = new Notification();
        $notification->setContent('Example Content');
        $user = UserMother::plainUser();
        $notification->setContact(ContactMother::withUser($user));
        $sendEmailNotification = new SendEmailNotification($notification->getId()->toString());

        $this->notificationRepositoryMock
            ->method('findOneBy')
            ->willReturn($notification);

        $this->mailerMock->expects(self::once())->method('send');
        $this->emailNotificationHandler->__invoke($sendEmailNotification);


        self::assertEquals($notification->getStatus(), MessageStatus::SENT);
    }

    public function test_if_will_update_status_to_failed_and_logs_the_error_on_transport_exception()
    {
        $user = UserMother::plainUser();
        $notification = new Notification();
        $notification->setContact(ContactMother::withUser($user));
        $notification->setContent('Example Content');
        $sendEmailNotification = new SendEmailNotification($notification->getId()->toString());
        $this->notificationRepositoryMock->method('findOneBy')->willReturn($notification);

        $handlerExceptionMock = new HandlerFailedException(new Envelope(new \stdClass()), [new \Exception()]);

        $this->mailerMock->expects(self::once())
            ->method('send')
            ->willThrowException($handlerExceptionMock);

        $this->loggerMock->expects(self::once())->method('log');

        try {
            $this->emailNotificationHandler->__invoke($sendEmailNotification);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(HandlerFailedException::class, $exception);
        } finally {
            self::assertEquals($notification->getStatus(), MessageStatus::FAILED);
        }
    }
}