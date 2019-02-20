<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoarticle')
            ->add('discriminant')
            ->add('ficnum')
            ->add('notebp')
            ->add('referencecindoc')
            ->add('n_v')
            ->add('c_g')
            ->add('idville')
            ->add('iddate')
            ->add('idoeuvre')
            ->add('idsujet')
            ->add('idico')
            ->add('idcliche')
            ->add('Insert/Update', SubmitType::class, ['label' => 'Insert/Update'])
            ->add('Delete', SubmitType::class, ['label' => 'Delete'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
