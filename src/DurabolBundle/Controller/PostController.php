<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('DurabolBundle:Post')->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('DurabolBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setTime(new \DateTime('now'))->setReadNum(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush($post);

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     */
    public function editAction(Request $request, Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('DurabolBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush($post);
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function fontIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));

        $posts = $em->getRepository('DurabolBundle:Post')->findByIsEs(false);

        return $this->render('DurabolBundle:Default:postIndex.html.twig', array(
            'shops' => $shops,
            'posts' => $posts,
        ));
    }

    public function fontIndexEsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));

        $posts = $em->getRepository('DurabolBundle:Post')->findByIsEs(true);

        return $this->render('DurabolBundle:DefaultEs:postIndex.html.twig', array(
            'shops' => $shops,
            'posts' => $posts,
        ));
    }
    
    public function fontShowAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));

        $post->setReadNum($post->getReadNum()+1);
        $em->persist($post);
        $em->flush();

        return $this->render('DurabolBundle:Default:post.html.twig', array(
            'shops' => $shops,
            'post' => $post,
        ));
    }

    public function fontShowEsAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));

        $post->setReadNum($post->getReadNum()+1);
        $em->persist($post);
        $em->flush();

        return $this->render('DurabolBundle:Default:post.html.twig', array(
            'shops' => $shops,
            'post' => $post,
            'isEs' => 1,
        ));
    }
}
