<?php

namespace App\Form;

use App\Entity\Cliche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClicheFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idcliche')
            ->add('taille')
            ->add("Insert/Update", SubmitType::class, ['label' => 'Insert/Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliche::class,
        ]);
    }
}
