<?php

declare(strict_types=1);

namespace App\MessageBus;

use App\Entity\Notification;
use App\Message\EmailMessageFactory;
use App\Message\MessageStatus;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationsHandler implements MessageHandlerInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private NotificationRepository $notificationRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(SendEmailNotification $emailNotification): void
    {
        /** @var Notification $notification */
        $notification = $this->notificationRepository->findOneBy([
            'id' => $emailNotification->getNotificationId(),
        ]);

        if ($notification->getContact() === null) {
            $this->logger->log(
                LogLevel::INFO,
                sprintf(
                    'Contact assigned to notification %s does not exists anymore. ',
                    $notification->getId()->toString()
                )
            );

            return;
        }

        try {
            $this->mailer->send(
                EmailMessageFactory::create(
                    $notification->getContact()->getEmail(),
                    $notification->getContent()
                )
            );
            $this->setStatusAndFlush($notification, MessageStatus::SENT);
        } catch (HandlerFailedException $e) {
            if ($e->getPrevious() !== null) {
                $this->logger->log(LogLevel::ERROR, $e->getPrevious()->getMessage());
            }
            $this->setStatusAndFlush($notification, MessageStatus::FAILED);

            throw $e;
        }
    }

    private function setStatusAndFlush(Notification $notification, string $status): void
    {
        $notification->setStatus($status);
        $this->entityManager->flush();
    }
}
