<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Month;
use App\Entity\Transaction;
use App\Entity\TransactionType as TransactionTypeEntity;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;

class TransactionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $months = $options['months'];
        $url = $options['url'];

        $builder
            ->add('date', DateType::class, [
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'placeholder'    => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'required'       => false,
            ])
            ->add('month', EntityType::class, [
                'class' => Month::class,
                'choices' => $months,
                'choice_label' => function ($month) use ($user) {
                    // Utilisez une fonction anonyme pour afficher la date au lieu du nom du mois
                    if ($month->getUser() === $user) {
                        return $month->getDate()->format('F Y');
                    }
                    // 'F' affiche le nom complet du mois, 'Y' affiche l'année
                },
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'placeholder'    => 'Select a month',
                'required'       => false,
            ])
            ->add('name', TextType::class, [
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'required'       => false,
            ])
            ->add('amount', NumberType::class, [
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'required'       => false,
            ])
            ->add('type', EntityType::class, [
                'class' => TransactionTypeEntity::class,
                'choice_label' => 'name',
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'placeholder'    => 'Choose a type of transaction',
                'required'       => false,
            ])
            ->add('budgetCategory', EntityType::class, [
                'class' => Budget::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('b')
                        ->where('b.user = :user')
                        ->setParameter('user', $user);
                },
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'placeholder'    => 'Select a budget type',
                'required'       => false,
            ])
            ->add('save', SubmitType::class, [
                'label'         => 'Save',
                'attr'           => ['class' => 'button-action button-action-filter js-button-action-save', 'data-href' => $url],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
            ])
            ->add('limit', ChoiceType::class, [
                'choices'        => [
                    '25' => 25,
                    '50' => 50,
                    '100' => 100,
                    '150' => 150,
                    '200' => 200,
                ],
                'label'          => 'Limit',
                'label_attr'     => ['class' => 'transaction-filter-form-row__label'],
                'attr'           => ['class' => 'transaction-filter-form-row__input'],
                'row_attr'       => ['class' => 'transaction-filter-form-row'],
                'required'       => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null,
            'months' => null,
            'url' => null,
            'attr' => ['class' => 'transaction-filter-form', 'id' => 'transaction-filter-form']
        ]);
    }
}
