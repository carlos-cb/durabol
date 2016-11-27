<?php

namespace DurabolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DurabolBundle\Entity\Product;

class SaleController extends Controller
{
    public function selectSaleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT p FROM DurabolBundle:Product p WHERE p.isSale=1");
        $products = $query->getResult();
        return $this->render('sale/index.html.twig', array(
            'products' => $products,
        ));
    }

    public function addSaleAction(Request $request)
    {
        $codigo = $request->request->get('codigo');
        $price = $request->request->get('price');
        $repository = $this->getDoctrine()->getRepository('DurabolBundle:Product');
        $product = $repository->findOneByCode($codigo);
        if($product){
            if($product->getIsSale()){
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    '该产品已经在新产品列表中'
                );
            }else{
                $product->setIsSale(true);
                $product->setDiscountPrice($price);
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    '添加成功'
                );
            }
        }else{
            $this->get('session')->getFlashBag()->add(
                'notice',
                '添加失败，请检查产品编号是否正确'
            );
        }
        return $this->redirectToRoute('select_sale');
    }

    public function deleteSaleAction(Product $product)
    {
        if($product){
            if(!$product->getIsSale()){
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    '该产品已经从打折产品列表中取消'
                );
            }else{
                $product->setIsSale(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    '取消成功'
                );
            }
        }else{
            $this->get('session')->getFlashBag()->add(
                'notice',
                '取消失败，请检查产品编号是否正确'
            );
        }
        return $this->redirectToRoute('select_sale');
    }
}
