<?php

namespace App\Controller;

use App\FormType\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);

        return $this->render('base.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
