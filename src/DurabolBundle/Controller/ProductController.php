<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Product;
use DurabolBundle\Entity\Category;
use DurabolBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    public function selectShopAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('product/selectShop.html.twig', array(
            'shops' => $shops,
        ));
    }

    public function selectCategoryAction(Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('DurabolBundle:Category')->findBy(array('shop' => $shop));

        return $this->render('product/selectCategory.html.twig', array(
            'shop' => $shop,
            'categories' => $categories,
        ));
    }

    /**
     * Lists all product entities.
     *
     */
    public function indexAction(Shop $shop, Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('DurabolBundle:Product')->findBy(array('category' => $category));

        return $this->render('product/index.html.twig', array(
            'shop' => $shop,
            'category' => $category,
            'products' => $products,
        ));
    }

    /**
     * Creates a new product entity.
     *
     */
    public function newAction(Request $request, Shop $shop, Category $category)
    {
        $product = new Product();
        $product->setCategory($category);
        $form = $this->createForm('DurabolBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDiscountPrice($product->getPrice())->setIsSale(0);

            $file = $product->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $product->setFoto($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush($product);

            return $this->redirectToRoute('product_index', array('shop' => $shop->getId(), 'category' => $category->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'shop' => $shop,
            'category' => $category,
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     */
    public function editAction(Request $request, Product $product, Shop $shop, Category $category)
    {
        $fileOld = $product->getFoto();
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('DurabolBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $product->getFoto();
            if($file != $fileOld)
            {
                $isRemoved = $this->get('durabol.foto_uploader')->remove($fileOld);
                if($isRemoved){
                    $fileName = $this->get('durabol.foto_uploader')->upload($file);
                    $product->setFoto($fileName);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_index', array('shop' => $shop->getId(), 'category' => $category->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'shop' => $shop,
            'category' => $category,
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);
        $category = $product->getCategory();
        $shop = $category->getShop();

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $product->getFoto();
            if($file){
                $isRemoved = $this->get('durabol.foto_uploader')->remove($file);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush($product);
        }

        return $this->redirectToRoute('product_index', array('shop' => $shop->getId(), 'category' => $category->getId()));
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}