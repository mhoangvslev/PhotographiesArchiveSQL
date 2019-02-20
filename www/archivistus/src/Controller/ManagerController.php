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
use App\Form\DocumentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            default:
                $formClass =  DocumentFormType::class;
                break;
        }

        $form = $this->createForm($formClass);
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
            ->add("Insert", SubmitType::class, ['label' => 'Insert'])
            ->add("Update", SubmitType::class, ['label' => 'Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
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
            if($dbForm->getClickedButton() && 'Insert' === $dbForm->getClickedButton()->getName()) {
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
