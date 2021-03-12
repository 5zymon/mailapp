<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Notification;
use App\MessageBus\SendEmailNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(SendEmailRequest $request): void
    {
        $notifications = [];
        foreach ($request->getRecipient()->getContacts() as $contact) {
            $notification = new Notification();
            $notification->setContact($contact);
            $notification->setContent($request->getContent());

            $this->entityManager->persist($notification);

            $notifications[] = $notification;
        }

        $this->entityManager->flush();
        $this->dispatchCreatedNotifications($notifications);
    }

    /**
     * @param array<int, Notification> $notifications
     */
    private function dispatchCreatedNotifications(array $notifications): void
    {
        foreach ($notifications as $notification) {
            $this->messageBus->dispatch(new SendEmailNotification($notification->getId()->toString()));
        }
    }
}
