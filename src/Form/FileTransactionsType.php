<?php

namespace App\Form;

use App\Entity\Month;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FileTransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Upload CSV File',
                'mapped' => false, // Ce champ n'est pas lié à la propriété de l'objet
                'required' => true,
                'attr'           => ['class' => 'file-transactions-form-row__input'],
                'row_attr'       => ['class' => 'file-transactions-form-row'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                            'application/vnd.ms-excel',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid CSV file',
                    ])
                ],
            ])
            ->add('month', EntityType::class, [
                'class' => Month::class,
                'choices' => $options['months'],
                'choice_label'   => function ($month) {
                    $data = null;
                    if ($month->isLocked() !== true) {
                        $data = $month->getDate()->format('F Y');
                    }
                    return $data;
                },
                'label' => 'Select Month',
                'placeholder' => 'Choose a Month',
                'attr'           => ['class' => 'file-transactions-form-row__input'],
                'row_attr'       => ['class' => 'file-transactions-form-row'],
                'required'       => true,
            ])
            ->add('save', SubmitType::class, [
                'label'         => 'Upload',
                'attr'           => ['class' => 'file-transactions-form-row__input'],
                'row_attr'       => ['class' => 'file-transactions-form-row'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'months' => null,
            'attr' => ['class' => 'file-transactions-form', 'id' => 'file-transactions-form']
        ]);
    }
}
