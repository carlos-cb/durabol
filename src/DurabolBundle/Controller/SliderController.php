<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slider controller.
 *
 */
class SliderController extends Controller
{
    /**
     * Lists all slider entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sliders = $em->getRepository('DurabolBundle:Slider')->findAll();

        return $this->render('slider/index.html.twig', array(
            'sliders' => $sliders,
        ));
    }

    /**
     * Creates a new slider entity.
     *
     */
    public function newAction(Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm('DurabolBundle\Form\SliderType', $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $slider->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $slider->setFoto($fileName);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush($slider);

            return $this->redirectToRoute('slider_index');
        }

        return $this->render('slider/new.html.twig', array(
            'slider' => $slider,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a slider entity.
     *
     */
    public function showAction(Slider $slider)
    {
        $deleteForm = $this->createDeleteForm($slider);

        return $this->render('slider/show.html.twig', array(
            'slider' => $slider,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing slider entity.
     *
     */
    public function editAction(Request $request, Slider $slider)
    {
        $fileOld = $slider->getFoto();
        $deleteForm = $this->createDeleteForm($slider);
        $editForm = $this->createForm('DurabolBundle\Form\SliderType', $slider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $slider->getFoto();
            if($file != $fileOld)
            {
                $isRemoved = $this->get('durabol.foto_uploader')->remove($fileOld);
                if($isRemoved){
                    $fileName = $this->get('durabol.foto_uploader')->upload($file);
                    $slider->setFoto($fileName);
                }
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slider_index');
        }

        return $this->render('slider/edit.html.twig', array(
            'slider' => $slider,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a slider entity.
     *
     */
    public function deleteAction(Request $request, Slider $slider)
    {
        $form = $this->createDeleteForm($slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $slider->getFoto();
            if($file){
                $isRemoved = $this->get('durabol.foto_uploader')->remove($file);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush($slider);
        }

        return $this->redirectToRoute('slider_index');
    }

    /**
     * Creates a form to delete a slider entity.
     *
     * @param Slider $slider The slider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slider $slider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('slider_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
