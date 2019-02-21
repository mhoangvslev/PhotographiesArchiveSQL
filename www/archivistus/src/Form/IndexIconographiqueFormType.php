<?php

namespace App\Form;

use App\Entity\IndexIconographique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexIconographiqueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idIco')
            ->add('idx_ico')
            ->add("Insert/Update", SubmitType::class, ['label' => 'Insert/Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IndexIconographique::class,
        ]);
    }
}
