<?php

declare(strict_types=1);

namespace App\FormType;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactFormType extends BaseType
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction($this->urlGenerator->generate('send_message'))
            ->add('recipient', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('content', TextareaType::class, [
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }
}
