<?php

namespace App\Form;

use App\Entity\Photo;
use App\Entity\Serie;
use App\Entity\TypeOeuvre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Article')
            ->add('remarques')
            ->add('nbrcli')
            ->add('descdet')
            ->add('idserie', EntityType::class, array(
                'class' => Serie::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nomSerie', 'ASC');
                },
                'choice_label' => 'nomSerie'
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
            'data_class' => Photo::class,
        ]);
    }
}
