<?php

namespace App\Form;

use App\Entity\TypeOeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeOeuvreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idType')
            ->add('nomType')
            ->add("Insert/Update", SubmitType::class, ['label' => 'Insert/Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeOeuvre::class,
        ]);
    }
}
