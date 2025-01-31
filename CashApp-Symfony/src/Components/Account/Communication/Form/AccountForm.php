<?php

namespace App\Components\Account\Communication\Form;

use App\Validator\Account\DailyLimit;
use App\Validator\Account\HourlyLimit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', FormType\MoneyType::class, [
                'label' => 'Amount',
                'currency' => false,
                'attr' => [
                    'required' => true,
                    'class' => 'form_input',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                    new Assert\Type('numeric'),
                    new DailyLimit(),
                    new HourlyLimit(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
