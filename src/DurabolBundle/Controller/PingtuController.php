<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Pingtu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pingtu controller.
 *
 */
class PingtuController extends Controller
{
    /**
     * Lists all pingtu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pingtus = $em->getRepository('DurabolBundle:Pingtu')->findAll();
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('pingtu/index.html.twig', array(
            'pingtus' => $pingtus,
            'shops' => $shops,
        ));
    }

    /**
     * Creates a new pingtu entity.
     *
     */
    public function newAction(Request $request)
    {
        $pingtu = new Pingtu();
        $form = $this->createForm('DurabolBundle\Form\PingtuType', $pingtu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $pingtu->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $pingtu->setFoto($fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($pingtu);
            $em->flush($pingtu);

            return $this->redirectToRoute('pingtu_index');
        }

        return $this->render('pingtu/new.html.twig', array(
            'pingtu' => $pingtu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pingtu entity.
     *
     */
    public function showAction(Pingtu $pingtu)
    {
        $deleteForm = $this->createDeleteForm($pingtu);

        return $this->render('pingtu/show.html.twig', array(
            'pingtu' => $pingtu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pingtu entity.
     *
     */
    public function editAction(Request $request, Pingtu $pingtu)
    {
        $fileOld = $pingtu->getFoto();
        $deleteForm = $this->createDeleteForm($pingtu);
        $editForm = $this->createForm('DurabolBundle\Form\PingtuType', $pingtu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $pingtu->getFoto();
            if($file != $fileOld)
            {
                $isRemoved = $this->get('durabol.foto_uploader')->remove($fileOld);
                if($isRemoved){
                    $fileName = $this->get('durabol.foto_uploader')->upload($file);
                    $pingtu->setFoto($fileName);
                }
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pingtu_index');
        }

        return $this->render('pingtu/edit.html.twig', array(
            'pingtu' => $pingtu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pingtu entity.
     *
     */
    public function deleteAction(Request $request, Pingtu $pingtu)
    {
        $form = $this->createDeleteForm($pingtu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pingtu);
            $em->flush($pingtu);
        }

        return $this->redirectToRoute('pingtu_index');
    }

    /**
     * Creates a form to delete a pingtu entity.
     *
     * @param Pingtu $pingtu The pingtu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pingtu $pingtu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pingtu_delete', array('id' => $pingtu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
