<?php

namespace App\Form;

use App\Entity\Cliche;
use App\Entity\DatePhoto;
use App\Entity\Document;
use App\Entity\IndexIconographique;
use App\Entity\IndexPersonne;
use App\Entity\Sujet;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('idville', EntityType::class, array(
                'class' => Ville::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nomVille', 'ASC');
                },
                'choice_label' => 'nomVille'
            ))
            ->add('idico', EntityType::class, array(
                'class' => IndexIconographique::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.idx_ico', 'ASC');
                },
                'choice_label' => 'idx_ico'
            ))
            ->add('iddate', EntityType::class, array(
                'class' => DatePhoto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.iddate', 'ASC');
                },
                'choice_label' => 'iddate'
            ))
            ->add('idoeuvre', EntityType::class, array(
                'class' => IndexPersonne::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nomoeuvre', 'ASC');
                },
                'choice_label' => 'nomoeuvre'
            ))
            ->add('idsujet', EntityType::class, array(
                'class' => Sujet::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.descSujet', 'ASC');
                },
                'choice_label' => 'descSujet'
            ))
            ->add('idcliche', EntityType::class, array(
                'class' => Cliche::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.taille', 'ASC');
                },
                'choice_label' => 'taille'
            ))
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
            'data_class' => Document::class,
        ]);

    }
}
