<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Month;
use App\Entity\Transaction;
use App\Entity\TransactionType as TransactionTypeEntity;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $months = $options['months'];
        $newBudgetIsEnable = $options['new_budget_is_enable'];

        $builder
            ->add('date', DateType::class, [
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
            ->add('month', EntityType::class, [
                'class' => Month::class,
                'choices' => $months,
                'choice_label' => function ($month) use ($user) {
                    if ($month->getUser() === $user) {
                        return $month->getDate()->format('F Y');
                    }
                },
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
            ->add('name', TextType::class, [
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
            ->add('amount', NumberType::class, [
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
            ->add('type', EntityType::class, [
                'class' => TransactionTypeEntity::class,
                'choice_label' => 'name',
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
            ->add('budgetCategory', EntityType::class, [
                'class' => Budget::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('b')
                        ->where('b.user = :user')
                        ->setParameter('user', $user);
                },
                'help'           => '(Select existing budget or add new one)',
                'label_attr'     => ['class' => 'transaction-form-row__label'],
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
                'required'       => !$newBudgetIsEnable
            ]);
            if ($newBudgetIsEnable) {
                $builder
                ->add('newBudgetName', TextType::class, [
                    'label'          => 'Define a name',
                    'label_attr'     => ['class' => 'transaction-form-row__label'],
                    'attr'           => ['class' => 'transaction-form-row__input'],
                    'row_attr'       => ['class' => 'transaction-form-row'],
                    'required'       => false
                ])
                ->add('newBudgetAmount', NumberType::class, [
                    'label'          => 'Enter an amount',
                    'label_attr'     => ['class' => 'transaction-form-row__label'],
                    'attr'           => ['class' => 'transaction-form-row__input'],
                    'row_attr'       => ['class' => 'transaction-form-row'],
                    'required'       => false
                ]);
            }
            $builder->add('save', SubmitType::class, [
                'label'         => 'Save',
                'attr'           => ['class' => 'transaction-form-row__input'],
                'row_attr'       => ['class' => 'transaction-form-row'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'user' => null,
            'months' => null,
            'new_budget_is_enable' => null,
            'attr' => ['class' => 'transaction-form', 'id' => 'transaction-form']
        ]);
    }
}
