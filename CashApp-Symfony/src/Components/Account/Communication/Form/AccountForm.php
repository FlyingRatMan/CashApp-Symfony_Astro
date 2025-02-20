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
                'attr' => [
                    'required' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(null, 'Amount should be VALID'),
                    new DailyLimit(),
                    new HourlyLimit(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => false,
        ]);
    }
}
