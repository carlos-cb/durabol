<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Shop controller.
 *
 */
class ShopController extends Controller
{
    /**
     * Lists all shop entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('shop/index.html.twig', array(
            'shops' => $shops,
        ));
    }

    /**
     * Creates a new shop entity.
     *
     */
    public function newAction(Request $request)
    {
        $shop = new Shop();
        $form = $this->createForm('DurabolBundle\Form\ShopType', $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $shop->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $shop->setFoto($fileName)->setIsTop(0);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($shop);
            $em->flush($shop);

            return $this->redirectToRoute('shop_index');
        }

        return $this->render('shop/new.html.twig', array(
            'shop' => $shop,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shop entity.
     *
     */
    public function showAction(Shop $shop)
    {
        $deleteForm = $this->createDeleteForm($shop);

        return $this->render('shop/show.html.twig', array(
            'shop' => $shop,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shop entity.
     *
     */
    public function editAction(Request $request, Shop $shop)
    {
        $fileOld = $shop->getFoto();
        $deleteForm = $this->createDeleteForm($shop);
        $editForm = $this->createForm('DurabolBundle\Form\ShopType', $shop);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $shop->getFoto();
            if($file != $fileOld)
            {
                $isRemoved = $this->get('durabol.foto_uploader')->remove($fileOld);
                if($isRemoved){
                    $fileName = $this->get('durabol.foto_uploader')->upload($file);
                    $shop->setFoto($fileName);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($shop);
            $em->flush();

            return $this->redirectToRoute('shop_index');
        }

        return $this->render('shop/edit.html.twig', array(
            'shop' => $shop,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a shop entity.
     *
     */
    public function deleteAction(Request $request, Shop $shop)
    {
        $form = $this->createDeleteForm($shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $shop->getFoto();
            if($file){
                $isRemoved = $this->get('durabol.foto_uploader')->remove($file);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($shop);
            $em->flush($shop);
        }

        return $this->redirectToRoute('shop_index');
    }

    /**
     * Creates a form to delete a shop entity.
     *
     * @param Shop $shop The shop entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shop $shop)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shop_delete', array('id' => $shop->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function isTopAction(Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();
        $shop->setIsTop(!$shop->getIsTop());
        $em->persist($shop);
        $em->flush();

        return $this->redirectToRoute('shop_index');
    }

    public function allMinCosteAction()
    {
        $em = $this->getDoctrine()->getManager();

        $allMinCoste = $em->getRepository('DurabolBundle:Globals')->findOneBy(array('name' => 'allMinCoste'))->getValue();

        return $this->render('shop/allMinCoste.html.twig', array(
            'allMinCoste' => $allMinCoste,
        ));
    }

    public function allMinCosteEditAction(Request $request)
    {
        $man = $request->request->get('codigo');

        $em = $this->getDoctrine()->getManager();
        $allMinCoste = $em->getRepository('DurabolBundle:Globals')->findOneBy(array('name' => 'allMinCoste'));

        $allMinCoste->setValue($man);
        $em->persist($allMinCoste);
        $em->flush();

        return $this->redirectToRoute('shop_allMinCoste');
    }
}
