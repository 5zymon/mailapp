<?php

declare(strict_types=1);

namespace App\Controller;

use App\FormType\ContactFormType;
use App\Message\Handler;
use App\Message\SendEmailRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    public function __construct(
        private Handler $messageHandler
    ) {
    }

    #[Route('/send', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): RedirectResponse
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data['recipient'] === null) {
                $this->addFlash('danger', 'Please provide existing recipient.');

                return new RedirectResponse($this->generateUrl('index'));
            }

            $this->messageHandler->handle(new SendEmailRequest($data['recipient'], $data['content']));
            $this->addFlash('success', 'Your Send Email Request has been received.');
        }

        return new RedirectResponse($this->generateUrl('index'));
    }
}
