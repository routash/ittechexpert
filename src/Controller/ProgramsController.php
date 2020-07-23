<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Entity\Programs;
use App\Form\PagesType;
use App\Form\ProgramsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProgramsController extends AbstractController
{
    /**
     * @Route("/programs", name="programs")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $programs = $entityManager->getRepository(Programs::class)->findAll();
        return $this->render('admin/programs/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/programs/add", name="add_programs")
     */
    public function add(Request $request)
    {

        $programs = new Programs();
        $form = $this->createForm(ProgramsType::class,$programs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($programs);
            $entityManager->flush();

            $this->addFlash('success', 'Program has been created.');
            return $this->redirectToRoute('programs');
        }

        return $this->render('admin/programs/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/programs/edit/{id}", name="programs_edit")
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $programs = $this->getDoctrine()->getRepository(Programs::class)->find($id);

        if (!$programs) {
            throw $this->notFoundException();
        }
        $form = $this->createForm(ProgramsType::class,$programs);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setUpdatedAt(new \DateTime());
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Program has been updated.');
        }

        return $this->render('admin/programs/edit.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/programs/delete/{id}", name="programs_delete")
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        $programs = $this->getDoctrine()->getRepository(Programs::class)->find($id);

        if (!$programs) {
            throw $this->notFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($programs);
        $em->flush();
        $this->addFlash('success', 'Programs has been Deleted.');
        return $this->redirectToRoute('programs');
    }
}
