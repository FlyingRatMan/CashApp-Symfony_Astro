<?php

declare(strict_types=1);

namespace App\Components\User\Communication\Form\Type;

use App\Validator\User\EmailIsUnique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FormType\TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'required' => true,
                    'class' => 'form_input',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('email', FormType\EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'required' => true,
                    'class' => 'form_input',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new EmailIsUnique(),
                ],
            ])
            ->add('password', FormType\PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'required' => true,
                    'class' => 'form_input',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/",
                        'message' => 'Password is not valid',
                    ]),
                ],
            ])
            ->add('registerBtn', FormType\SubmitType::class, [
                'label' => 'Sign in!',
                'attr' => [
                    'class' => 'form_primary-btn',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
