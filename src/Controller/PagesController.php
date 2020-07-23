<?php

namespace App\Controller;

use App\Entity\Pages;
use App\Form\PagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    /**
     * @Route("/pages", name="pages")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $pages = $entityManager->getRepository(Pages::class)->findAll();
        return $this->render('admin/pages/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/pages/add", name="add_pages")
     */
    public function add(Request $request)
    {
        $page = new Pages();
        $form = $this->createForm(PagesType::class,$page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            $this->addFlash('success', 'Page has been created.');
            return $this->redirectToRoute('pages');
        }

        return $this->render('admin/pages/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/page/edit/{id}", name="page_edit")
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $page = $this->getDoctrine()->getRepository(Pages::class)->find($id);

        if (!$page) {
            throw $this->notFoundException();
        }
        $form = $this->createForm(PagesType::class,$page);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setUpdatedAt(new \DateTime());
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Page has been updated.');
        }

        return $this->render('admin/pages/edit.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/page/delete/{id}", name="page_delete")
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        $page = $this->getDoctrine()->getRepository(Pages::class)->find($id);

        if (!$page) {
            throw $this->notFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        $this->addFlash('success', 'Page has been Deleted.');
        return $this->redirectToRoute('pages');
    }

}
