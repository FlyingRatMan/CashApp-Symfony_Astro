<?php

namespace App\Components\User\Communication\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordLinkRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', FormType\EmailType::class, [
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'form_input',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your email',
                    ]),
                    new Assert\Email(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
