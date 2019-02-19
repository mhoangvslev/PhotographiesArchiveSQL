<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\TableForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    /**
     * @Route("/manager", name="manager")
     */
    public function index(Request $request)
    {
        $tableForm = new TableForm();
        $tableForm->setTableName("Photo");
        $tableForm->setCondition("1");

        $form = $this->createFormBuilder($tableForm)
            ->add("tableName", TextType::class)
            ->add('condition', TextType::class)
            ->add("Search", SubmitType::class, ['label' => 'Search'])
            ->add("Insert", SubmitType::class, ['label' => 'Insert'])
            ->add("Update", SubmitType::class, ['label' => 'Update'])
            ->add("Delete", SubmitType::class, ['label' => 'Delete'])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            // Search case
            if($form->getClickedButton() && 'Search' === $form->getClickedButton()->getName()) {
                $query_result = $this->search($data);
            }

            // Insert case
            if($form->getClickedButton() && 'Insert' === $form->getClickedButton()->getName()) {
                $query_result = $this->insert($data);
            }

            // Update case
            if($form->getClickedButton() && 'Update' === $form->getClickedButton()->getName()) {
                $query_result = $this->update($data);
            }

            // Delete case
            if($form->getClickedButton() && 'Delete' === $form->getClickedButton()->getName()) {
                $query_result = $this->delete($data);
            }
        }

        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'tableForm' => $form->createView(),
        ]);
    }


    public function search($data)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $result = $entityManager->getRepository(Photo::class)->findAll();
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
