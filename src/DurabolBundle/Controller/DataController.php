<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Datum controller.
 *
 */
class DataController extends Controller
{
    /**
     * Lists all datum entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $datas = $em->getRepository('DurabolBundle:Data')->findAll();

        return $this->render('data/index.html.twig', array(
            'datas' => $datas,
        ));
    }

    /**
     * Creates a new datum entity.
     *
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        $datum = new Data();
        $form = $this->createForm('DurabolBundle\Form\DataType', $datum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($datum);
            $em->flush($datum);

            return $this->redirectToRoute('durabol_homepage');
        }

        return $this->render('data/new.html.twig', array(
            'shops' => $shops,
            'datum' => $datum,
            'form' => $form->createView(),
        ));
    }

    public function newEsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        $datum = new Data();
        $form = $this->createForm('DurabolBundle\Form\DataEsType', $datum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($datum);
            $em->flush($datum);

            return $this->redirectToRoute('durabol_homepage_es');
        }

        return $this->render('data/new.html.twig', array(
            'shops' => $shops,
            'datum' => $datum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a datum entity.
     *
     */
    public function showAction(Data $datum)
    {
        $deleteForm = $this->createDeleteForm($datum);

        return $this->render('data/show.html.twig', array(
            'datum' => $datum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing datum entity.
     *
     */
    public function editAction(Request $request, Data $datum)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        $deleteForm = $this->createDeleteForm($datum);
        $editForm = $this->createForm('DurabolBundle\Form\DataType', $datum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('data_edit', array('id' => $datum->getId()));
        }

        return $this->render('data/edit.html.twig', array(
            'shops' => $shops,
            'datum' => $datum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editEsAction(Request $request, Data $datum)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        $deleteForm = $this->createDeleteForm($datum);
        $editForm = $this->createForm('DurabolBundle\Form\DataEsType', $datum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('data_edit_es', array('id' => $datum->getId()));
        }

        return $this->render('data/editEs.html.twig', array(
            'shops' => $shops,
            'datum' => $datum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Deletes a datum entity.
     *
     */
    public function deleteAction(Request $request, Data $datum)
    {
        $form = $this->createDeleteForm($datum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($datum);
            $em->flush($datum);
        }

        return $this->redirectToRoute('data_index');
    }

    /**
     * Creates a form to delete a datum entity.
     *
     * @param Data $datum The datum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Data $datum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('data_delete', array('id' => $datum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
