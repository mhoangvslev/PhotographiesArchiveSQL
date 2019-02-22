<?php

namespace App\Form;

use App\Entity\Ville;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idVille')
            ->add('nomVille')
            ->add('latitude')
            ->add('longitude')
            ->add('coordx')
            ->add('coordy')
            ->add('queryLimit', IntegerType::class, array("mapped" => false,))
            ->add("Search", SubmitType::class, ['label' => 'Search'])
            ->add("Insert", SubmitType::class, ['label' => 'Insert'])
            ->add("Update", SubmitType::class, ['label' => 'Update'])
->add("Delete", SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
