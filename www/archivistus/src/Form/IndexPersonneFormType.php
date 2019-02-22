<?php

namespace App\Form;

use App\Entity\EntityDataTransformer;
use App\Entity\IndexPersonne;
use App\Entity\TypeOeuvre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndexPersonneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idOeuvre')
            ->add('nomOeuvre')
            ->add('typeoeuvre', EntityType::class, array(
                'class' => TypeOeuvre::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('to')
                        ->orderBy('to.nomType', 'ASC');
                },
                'choice_label' => 'nomType'
            ))
            /*->add('typeoeuvre', IntegerType::class)
            ->get('typeoeuvre')->addModelTransformer(new EntityDataTransformer($options['entity_manager']))*/
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
            'compound' => true,
            'data_class' => IndexPersonne::class,
        ]);

    }
}
