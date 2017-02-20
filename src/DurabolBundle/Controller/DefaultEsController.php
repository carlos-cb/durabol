<?php

namespace DurabolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DurabolBundle\Entity\Cart;
use DurabolBundle\Entity\CartItem;
use DurabolBundle\Entity\Shop;
use DurabolBundle\Entity\Category;
use DurabolBundle\Entity\OrderInfo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultEsController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $isUserClient = $em->getRepository('DurabolBundle:User')->findOneBy(array('id' => $user));
        if($isUserClient){
            if(!$user->getCart()){
                $cart = new Cart();
                $cart->setUser($user);
                $em->persist($cart);
                $em->flush();
                $cartItems = $cart->getCartItems();
            }else{
                $cart = $user->getCart();
                $cartItems = $cart->getCartItems();
            }
            $cartShops = array();
            $i = 0;
            foreach($cartItems as $cartItem)
            {
                $cartShops[$i] = $cartItem->getProduct()->getCategory()->getShop();
                $i ++;
                $cartShops = array_unique($cartShops);
            }
        }else{
            $cartItems = null;
            $cartShops = null;
        }
        $allMinCoste = $em->getRepository('DurabolBundle:Globals')->findOneBy(array('name' => 'allMinCoste'))->getValue();
        $productSales = $em->getRepository('DurabolBundle:Product')->findBy(array('isTop' => '1'));
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));
        $sliders = $em->getRepository('DurabolBundle:Slider')->findByIsEs(true);
        $pingtus = $em->getRepository('DurabolBundle:Pingtu')->findAll();

        return $this->render('DurabolBundle:DefaultEs:index.html.twig', array(
            'productSales' => $productSales,
            'shops' => $shops,
            'user' => $user,
            'cartItems' => $cartItems,
            'cartShops' => $cartShops,
            'sliders' => $sliders,
            'pingtus' => $pingtus,
            'allMinCoste' => $allMinCoste,
        ));
    }

    public function productListAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if(!$user->getCart())
        {
            $cart = new Cart();
            $cart->setUser($user);
            $em->persist($cart);
            $em->flush();
        }
        $products = $em->getRepository('DurabolBundle:Product')->findBy(
            array('category' => $category, 'isShow' => true),
            array('isTop' => 'DESC')
        );
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        $cart = $this->getUser()->getCart();
        $cartItems = $cart->getCartItems();

        return $this->render('DurabolBundle:DefaultEs:productlist.html.twig', array(
            'user' => $user,
            'shops' => $shops,
            'products' => $products,
            'cartItems' => $cartItems,
        ));
    }

    public function categoryListAction(Shop $shop)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('DurabolBundle:Category')->findByShop($shop, array('isTop' => 'DESC'));
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();
        
        return $this->render('DurabolBundle:DefaultEs:category.html.twig', array(
            'categories' => $categories,
            'shops' => $shops,
            'thisShop' => $shop,
        ));
    }

    public function guestinfoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        $user = $this->getUser();
        $cartItems = $user->getCart()->getCartItems();
        $delivery = true;
        $payOnline = true;
        foreach ($cartItems as $cartItem){
            if(!$cartItem->getProduct()->getCategory()->getShop()->getIsTop()){
                $delivery = false;
                break;
            }
        }
        foreach ($cartItems as $cartItem){
            if(!$cartItem->getProduct()->getCategory()->getShop()->getIsPayOnline()){
                $payOnline = false;
                break;
            }
        }
        $data = $user->getData();

        return $this->render('DurabolBundle:DefaultEs:guestinfo.html.twig', array(
            'user' => $user,
            'data' => $data,
            'shops' => $shops,
            'delivery' => $delivery,
            'payOnline' => $payOnline,
        ));
    }

    public function pedidoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository('DurabolBundle:OrderInfo');
        $orderInfos = $repository->findByUser($user, array('orderDate' => 'DESC'));

        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('DurabolBundle:DefaultEs:pedido.html.twig', array(
            'orderInfos' => $orderInfos,
            'user' => $user,
            'shops' => $shops,
        ));
    }

    public function pedidoOtherAction($state)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if($state == 1)
        {
            $orderState = "未付款";
        }elseif($state == 2){
            $orderState = "已付款";
        }else{
            $orderState = "已完成";
        }
        $repository = $this->getDoctrine()->getRepository('DurabolBundle:OrderInfo');
        $orderInfos = $repository->findBy(
            array('user' => $user, 'state' => $orderState),
            array('orderDate' => 'DESC')
        );
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('DurabolBundle:DefaultEs:pedido.html.twig', array(
            'orderInfos' => $orderInfos,
            'user' => $user,
            'orderState' => $orderState,
            'shops' => $shops,
        ));
    }

    public function productlistclientAction(OrderInfo $orderInfo)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $query = $em->createQuery("SELECT p FROM DurabolBundle:OrderItem p WHERE p.orderInfo=$orderInfo");
        $orderItems = $query->getResult();
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('DurabolBundle:DefaultEs:productlistclient.html.twig', array(
            'orderItems' => $orderItems,
            'orderInfo' => $orderInfo,
            'user' => $user,
            'shops' => $shops,
        ));
    }

    public function infoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findAll();

        return $this->render('DurabolBundle:DefaultEs:info.html.twig', array(
            'shops' => $shops,
        ));
    }
}
