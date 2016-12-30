<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Category;
use DurabolBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     */
    public function indexAction(Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('DurabolBundle:Category')->findBy(array('shop' => $shop));

        return $this->render('category/index.html.twig', array(
            'shop' => $shop,
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     */
    public function newAction(Request $request, Shop $shop)
    {
        $category = new Category();
        $form = $this->createForm('DurabolBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $category->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $category->setFoto($fileName)->setIsTop(0);

            $category->setShop($shop);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index', array('shop' => $shop->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'shop' => $shop,
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     */
    public function editAction(Request $request, Category $category, Shop $shop)
    {
        $fileOld = $category->getFoto();
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('DurabolBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $category->getFoto();
            if($file != $fileOld)
            {
                $isRemoved = $this->get('durabol.foto_uploader')->remove($fileOld);
                if($isRemoved){
                    $fileName = $this->get('durabol.foto_uploader')->upload($file);
                    $category->setFoto($fileName);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index', array('shop' => $shop->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'shop' => $shop,
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);
        $shop = $category->getShop();

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $category->getFoto();
            
            if($file){
                $isRemoved = $this->get('durabol.foto_uploader')->remove($file);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush($category);
        }

        return $this->redirectToRoute('category_index', array('shop' => $shop->getId()));
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function isTopAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $category->setIsTop(!$category->getIsTop());
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('category_index', array('shop' => $category->getShop()->getId()));
    }
}
