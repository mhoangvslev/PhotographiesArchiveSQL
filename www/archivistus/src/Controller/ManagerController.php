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
use App\Entity\TypeOeuvre;
use App\Entity\Ville;
use App\Entity\Cliche;
use App\Form\ClicheFormType;
use App\Form\DocumentFormType;
use App\Form\DatePhotoFormType;
use App\Form\IndexIconographiqueFormType;
use App\Form\IndexPersonneFormType;
use App\Form\PhotoFormType;
use App\Form\SerieFormType;
use App\Form\SujetFormType;
use App\Form\TypeOeuvreFormType;
use App\Form\VilleFormType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ManagerController extends AbstractController
{
    /**
     * @Route("/manager/insert/{className}", name="managerInsert")
     */
    public function managerInsert(string $className, Request $request){
        $entityManager = $this->getDoctrine()->getManager();

        $query_result = array(
            'tableHeads' => $entityManager->getClassMetadata($className)->getFieldNames(),
            'tableContents' => $entityManager->getRepository($className)->findBy(array(), array(), 100, array()),
        );

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

            // Insert/Update case
            if($form->getClickedButton() && 'Insert' === $form->getClickedButton()->getName()) {
                $this->insert($data);
            }

            // Insert/Update case
            if($form->getClickedButton() && 'Update' === $form->getClickedButton()->getName()) {
                $this->update($data);
            }

            // Delete case
            if($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()) {
                $this->delete($data);
            }

            // Search case
            if($form->getClickedButton() && 'Search' === $form->getClickedButton()->getName()) {
                $query_result = $this->search(array(
                    'queryTable' => get_class($data),
                    'queryCriteria' => array_filter($data->toArray()),
                    'queryLimit' => $form->get('queryLimit')->getData()
                ));
            }
        }

        return $this->render('manager/EntityManager.html.twig', [
            'controller_name' => 'ManagerController',
            'tableName' => $className,
            'tableForm' => $form->createView(),
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
            ->add("Manage", SubmitType::class, ['label' => 'Manage'])
            ->getForm();

        $dbForm->handleRequest($request);
        if($dbForm->isSubmitted() && $dbForm->isValid()){
            $data = $dbForm->getData();

            // Manage
            if($dbForm->getClickedButton() && 'Manage' === $dbForm->getClickedButton()->getName()) {
                return $this->redirectToRoute('managerInsert', array('className' => $data->getTableName()));
            }
        }

        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'databaseForm' => $dbForm->createView(),
        ]);
    }


    public function search(array $query)
    {
        dump($query);

        $entityManager = $this->getDoctrine()->getManager();
        $result = array(
            'tableHeads' => $entityManager->getClassMetadata($query['queryTable'])->getFieldNames(),
            'tableContents' => $entityManager->getRepository($query['queryTable'])->findBy($query['queryCriteria'], array(), $query['queryLimit'], array()),
        );
        return $result;
    }

    public function insert($data)
    {
        /* à completer*/
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($data);
        $entityManager->flush();

        return array(
            'tableHeads' => $entityManager->getClassMetadata(get_class($data))->getFieldNames(),
            'tableContents' => $entityManager->getRepository(get_class($data))->findBy(array(), array(), 100, array()),
        );
    }

    public function update($data)
    {
        /* à completer*/
        $entityManager = $this->getDoctrine()->getManager();

        $entities = $entityManager->getRepository(get_class($data))->findBy(array_filter($data->toArray()), array(), array(), array());

        foreach ($entities as $entity){
            $entity->updateAll($data);
        }
        $entityManager->flush();

        return array(
            'tableHeads' => $entityManager->getClassMetadata(get_class($data))->getFieldNames(),
            'tableContents' => $entityManager->getRepository(get_class($data))->findBy(array(), array(), 100, array()),
        );
    }

    public function delete($data)
    {
        /* à completer*/
        $entityManager = $this->getDoctrine()->getManager();
        $data = $entityManager->merge($data);
        $entityManager->remove($data);
        $entityManager->flush();

        return array(
            'tableHeads' => $entityManager->getClassMetadata(get_class($data))->getFieldNames(),
            'tableContents' => $entityManager->getRepository(get_class($data))->findBy(array(), array(), 100, array()),
        );

    }

}
