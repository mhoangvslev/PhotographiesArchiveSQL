<?php

namespace App\Form;

use App\Entity\IndexPersonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexPersonneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idOeuvre')
            ->add('nomOeuvre')
            ->add('typeoeuvre')
            ->add("Insert/Update", SubmitType::class, ['label' => 'Insert/Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IndexPersonne::class,
        ]);
    }
}
