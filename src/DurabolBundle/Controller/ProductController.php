<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\Product;
use DurabolBundle\Entity\Category;
use DurabolBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    public function selectShopAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        if($user->isSuperAdmin()){
            return $this->render('product/selectShop.html.twig', array(
                'shops' => $shops,
            ));
        }else{
            return $this->redirectToRoute('product_selectCategory', array(
                'shop' => $user->getAdminShop(),
            ));
        }
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
     * Lists all product entities.
     *
     */
    public function indexAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('DurabolBundle:Product')->findAll();

        return $this->render('product/index.html.twig', array(
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
            $product->setIsTop(0);

            $file = $product->getFoto();
            $fileName = $this->get('durabol.foto_uploader')->upload($file);
            $product->setFoto($fileName)->setIsTop(0);

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

    public function isTopAction(Shop $shop, Category $category, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $product->setIsTop(!$product->getIsTop());
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('product_index', array('shop' => $shop->getId(), 'category' => $category->getId()));
    }

    public function importFileAction()
    {
        return $this->render('product/importFile.html.twig');
    }

    public function importTestAction(Request $request)
    {
        if($request->getMethod() == 'POST')
        {
            $excelFile = $request->files->get('file_stu');
            $datas = $this->import($excelFile);
            $session = new Session();
            $session->set('excel', $datas);

            return $this->render('product/importTest.html.twig', array(
                'datas' => $datas,
            ));
        }
        else{
            $errorMsg = '上传失败';
            
            return $this->render('product/importFile.html.twig', array(
                'errorMsg' => $errorMsg,
            ));
        }
    }

    public function importExcelAction()
    {
        $session = new Session();
        $datas = $session->get('excel');
        $em = $this->getDoctrine()->getManager();

        foreach ($datas as $data)
        {
            $product = new Product();
            $category = $em->getRepository('DurabolBundle:Category')->find((int)$data[4]);
            $product->setName($data[0])
                    ->setPrice($data[1])
                    ->setCode($data[2])
                    ->setFoto($data[3])
                    ->setCategory($category)
                    ->setUnit($data[5])
                    ->setIsSale($data[6])
                    ->setDiscountPrice($data[7])
                    ->setNameEs($data[8])
                    ->setIsShow(1)
                    ->setIsTop(0)
            ;
            $em->persist($product);
            $em->flush();
        }
        $session->remove('excel');

        return $this->redirectToRoute('product_indexAll');
    }

    private function import($excelFile)
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($excelFile);

        $objWorksheet = $phpExcelObject->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumnIndex = 9;
        $headtitle = array();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            $strs = array();
            for ($col = 0;$col < $highestColumnIndex;$col++)
            {
                $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            $headtitle[$row-2] = $strs;
        }
        return $headtitle;
    }
}
