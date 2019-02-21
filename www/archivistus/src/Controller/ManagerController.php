<?php

namespace App\Controller;

use App\Entity\DatePhoto;
use App\Entity\Document;
use App\Entity\IndexIconographique;
use App\Entity\IndexPersonne;
use App\Entity\Photo;
use App\Entity\Serie;
use App\Entity\Sujet;
use App\Entity\DatabaseForm;
use App\Entity\TableForm;
use App\Entity\TypeOeuvre;
use App\Entity\Ville;
use App\Entity\Cliche;
use App\Form\ClicheFormType;
use App\Form\DatePhotoFormType;
use App\Form\DocumentFormType;
use App\Form\IndexIconographiqueFormType;
use App\Form\IndexPersonneFormType;
use App\Form\PhotoFormType;
use App\Form\SerieFormType;
use App\Form\SujetFormType;
use App\Form\TypeOeuvreFormType;
use App\Form\VilleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ManagerController extends AbstractController
{

    /**
     * @Route("/manager/insert/{className}", name="managerInsert")
     */
    public function managerInsert(string $className, Request $request){
        switch ($className){
            case Document::class:
                $formClass = DocumentFormType::class;
                break;
            case Cliche::class:
                $formClass = ClicheFormType::class;
                break;
            case DatePhoto::class:
                $formClass = DatePhotoFormType::class;
                break;
            case IndexIconographique::class:
                $formClass = IndexIconographiqueFormType::class;
                break;
            case IndexPersonne::class:
                $formClass = IndexPersonneFormType::class;
                break;
            case Photo::class:
                $formClass = PhotoFormType::class;
                break;
            case Serie::class:
                $formClass = SerieFormType::class;
                break;
            case Sujet::class:
                $formClass = SujetFormType::class;
                break;
            case TypeOeuvre::class:
                $formClass = TypeOeuvreFormType::class;
                break;
            case Ville::class:
                $formClass = VilleFormType::class;
                break;
            default:
                $formClass =  DocumentFormType::class;
                break;
        }

        $form = $this->createForm($formClass);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            // Search case
            if($form->getClickedButton() && 'Insert/Update' === $form->getClickedButton()->getName()) {
            }

            // Search case
            if($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()) {
            }
        }


        $entityManager = $this->getDoctrine()->getManager();
        $this->fieldChoices = $entityManager->getClassMetadata($className)->getFieldNames();
        $query_result = array(
            'tableName' => $className,
            'tableHeads' => $entityManager->getClassMetadata($className)->getFieldNames(),
            'tableContents' => $entityManager->getRepository($className)->findBy(array(), array(), 10, array()),
        );

        return $this->render('manager/insertDocument.html.twig', [
            'controller_name' => 'ManagerController',
            'tableName' => $className,
            'tableForm' => $form->createView(),
            'query_name' => $query_result['tableName'],
            'query_field' => $query_result['tableHeads'],
            'query_result' => $query_result['tableContents'],
        ]);
    }

    /**
     * @Route("/manager", name="manager")
     */
    public function index(Request $request)
    {
        $databaseForm = new DatabaseForm();
        $dbForm = $this->createFormBuilder($databaseForm)
            ->add("tableName", ChoiceType::class, [
                'choices' => [
                    'Cliche' => Cliche::class,
                    'DatePhoto' => DatePhoto::class,
                    'Document' => Document::class,
                    'IndexIconographique' => IndexIconographique::class,
                    'IndexPersonne' => IndexPersonne::class,
                    'Photo' => Photo::class,
                    'Serie' => Serie::class,
                    'Sujet' => Sujet::class,
                    'TypeOeuvre' => TypeOeuvre::class,
                    'Ville' => Ville::class
                ]
            ])
            ->add('queryLimit', IntegerType::class)
            ->add("Search", SubmitType::class, ['label' => 'Search'])
            ->add("Manage", SubmitType::class, ['label' => 'Manage'])
            ->getForm();

        $query_result = array(
            'tableName' => null,
            'tableHeads' => null,
            'tableContents' => null
        );

        $dbForm->handleRequest($request);
        if($dbForm->isSubmitted() && $dbForm->isValid()){
            $data = $dbForm->getData();

            // Search case
            if($dbForm->getClickedButton() && 'Search' === $dbForm->getClickedButton()->getName()) {
                $query_result = $this->search($data);
            }

            // Search case
            if($dbForm->getClickedButton() && 'Manage' === $dbForm->getClickedButton()->getName()) {
                return $this->redirectToRoute('managerInsert', array('className' => $data->getTableName()));
            }
        }

        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'databaseForm' => $dbForm->createView(),
            //'tableForm' => $tbForm->createView(),
            'query_name' => $query_result['tableName'],
            'query_field' => $query_result['tableHeads'],
            'query_result' => $query_result['tableContents'],
        ]);
    }


    public function search($data)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->fieldChoices = $entityManager->getClassMetadata($data->getTableName())->getFieldNames();
        $result = array(
            'tableName' => $data->getTableName(),
            'tableHeads' => $entityManager->getClassMetadata($data->getTableName())->getFieldNames(),
            'tableContents' => $entityManager->getRepository($data->getTableName())->findBy(array(), array(), $data->getQueryLimit(), array()),
        );
        return $result;
    }

    public function insert($data)
    {
        /* à completer*/
    }

    public function update($data)
    {
        /* à completer*/
    }

    public function delete($data)
    {
        /* à completer*/

    }

}
