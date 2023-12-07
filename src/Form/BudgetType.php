<?php

namespace App\Form;

use App\Entity\Budget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr'     => ['class' => 'budget-form-row__label'],
                'attr'           => ['class' => 'budget-form-row__input'],
                'row_attr'       => ['class' => 'budget-form-row'],
            ])
            ->add('amount', NumberType::class, [
                'label_attr'     => ['class' => 'budget-form-row__label'],
                'attr'           => ['class' => 'budget-form-row__input'],
                'row_attr'       => ['class' => 'budget-form-row'],
            ])
            ->add('isSalary', CheckboxType::class, [
                'label'          => 'Is this a salary?',
                'label_attr'     => ['class' => 'budget-form-row__label'],
                'attr'           => ['class' => 'budget-form-row__input'],
                'row_attr'       => ['class' => 'budget-form-row budget-form-row-is-salary'],
                'required'       => false,
            ])
            ->add('save', SubmitType::class, [
                'label'         => 'Save',
                'attr'           => ['class' => 'budget-form-row__input'],
                'row_attr'       => ['class' => 'budget-form-row'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Budget::class,
            'attr' => ['class' => 'budget-form', 'id' => 'budget-form']
        ]);
    }
}
