<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Month;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('date', DateType::class, [
                'label_attr'     => ['class' => 'month-form-row__label'],
                'attr'           => ['class' => 'month-form-row__input'],
                'row_attr'       => ['class' => 'month-form-row'],
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month',
                ],
                'years' => range(date('Y') - 3, date('Y') + 5),
            ])
            ->add('budgets', EntityType::class, [
                'label' => 'Budgets',
                'class' => Budget::class,
                'choices' => $options['budgets'],
                'choice_label' => function ($budget) {
                    return $budget->getName();
                },
                'expanded' => true,
                'multiple' => true,
                'label_attr'     => ['class' => 'month-form-row__label'],
                'attr'           => ['class' => 'month-form-row__input month-form-row__input-budgets'],
                'row_attr'       => ['class' => 'month-form-row'],
                'required'       => false,
            ])
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
            'budgets' => null,
            'attr' => ['class' => 'month-form', 'id' => 'month-form']
        ]);
    }
}
