<?php

namespace App\Form;

use App\Entity\Month;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('name', TextType::class, [
//                'label_attr'     => ['class' => 'month-form-row__label'],
//                'attr'           => ['class' => 'month-form-row__input'],
//                'row_attr'       => ['class' => 'month-form-row'],
//            ])
            ->add('date', DateType::class, [
                'label_attr'     => ['class' => 'month-form-row__label'],
                'attr'           => ['class' => 'month-form-row__input'],
                'row_attr'       => ['class' => 'month-form-row'],
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month',
                ],
                'years' => range(date('Y') - 3, date('Y') + 5),
            ])
//            ->add('balance')
//            ->add('totalAmountSpent')
//            ->add('totalAmountEarned')
            ->add('save', SubmitType::class, [
                'label'         => 'Save',
                'attr'           => ['class' => 'month-form-row__input'],
                'row_attr'       => ['class' => 'month-form-row'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Month::class,
            'attr' => ['class' => 'month-form', 'id' => 'month-form']
        ]);
    }
}
