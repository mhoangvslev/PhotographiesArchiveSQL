<?php

namespace App\Form;

use App\Entity\DatePhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataPhotoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idDate')
            ->add('dateJour')
            ->add('dateMois')
            ->add('dateAnnee')
            ->add("Insert/Update", SubmitType::class, ['label' => 'Insert/Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DatePhoto::class,
        ]);
    }
}
