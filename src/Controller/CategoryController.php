<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $category = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categoryList' => $category,
        ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function add(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $category->getTitle();
            $slug = $category->getSlug();
            $existCategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('title'=>$title));
            $existSlug = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('slug'=>$slug));
            if($existCategory != null){
                $this->addFlash('danger', 'Category Title Already exist.');
                return $this->redirectToRoute('category_add');
            }
            if($existSlug != null){
                $this->addFlash('danger', 'Category Slug Already exist.');
                return $this->redirectToRoute('category_add');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category has been created.');
            return $this->redirectToRoute('category');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->notFoundException();
        }
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $categoryData = $form->getData();
            $categoryData->setUpdatedAt(new \DateTime());
            $em->persist($categoryData);
            $em->flush();
            $this->addFlash('success', 'Category has been updated.');
        }

        return $this->render('admin/category/edit.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->notFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category has been Deleted.');
        return $this->redirectToRoute('category');
    }
}
