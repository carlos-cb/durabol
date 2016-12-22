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

class DefaultController extends Controller
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

        $productSales = $em->getRepository('DurabolBundle:Product')->findBy(array('isTop' => '1'));
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('turn' => 'DESC'));
        $sliders = $em->getRepository('DurabolBundle:Slider')->findAll();
        $pingtus = $em->getRepository('DurabolBundle:Pingtu')->findAll();

        return $this->render('DurabolBundle:Default:index.html.twig', array(
            'productSales' => $productSales,
            'shops' => $shops,
            'user' => $user,
            'cartItems' => $cartItems,
            'cartShops' => $cartShops,
            'sliders' => $sliders,
            'pingtus' => $pingtus,
        ));
    }

    public function backEndAction()
    {
        $em = $this->getDoctrine()->getManager();

        $numUser = $em->getRepository('DurabolBundle:User')->createQueryBuilder('a')->select('COUNT(a.id)')->getQuery()->getSingleScalarResult();
        $numOrder = $em->getRepository('DurabolBundle:OrderInfo')->createQueryBuilder('b')->select('COUNT(b.id)')->getQuery()->getSingleScalarResult();
        $numProduct = $em->getRepository('DurabolBundle:Product')->createQueryBuilder('c')->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();
        $numShop = $em->getRepository('DurabolBundle:Shop')->createQueryBuilder('s')->select('COUNT(s.id)')->getQuery()->getSingleScalarResult();

        $queryU = $em->createQuery("SELECT p FROM DurabolBundle:User p WHERE 1=1 order by p.id DESC")->setMaxResults(10);
        $users = $queryU->getResult();

        $queryO = $em->createQuery("SELECT t FROM DurabolBundle:OrderInfo t WHERE 1=1 order by t.id DESC")->setMaxResults(10);
        $orders = $queryO->getResult();

        $day6Orders = array();
        for($i=0; $i<6; $i++){
            $queryday6Orders[$i] = $em->createQuery("SELECT COUNT(o) FROM DurabolBundle:OrderInfo o where o.orderDate <= DATE_ADD(CURRENT_DATE(), (1-$i), 'day') and o.orderDate >= DATE_SUB(CURRENT_DATE(), $i, 'day')");
            $day6Orders[$i] = $queryday6Orders[$i]->getSingleScalarResult();
        }

        return $this->render('DurabolBundle:BackEnd:overview.html.twig', array(
            'numShop' => $numShop,
            'numUser' => $numUser,
            'numOrder' => $numOrder,
            'numProduct' => $numProduct,
            'users' => $users,
            'orders' => $orders,
            'day6Orders' => $day6Orders,
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
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        $cart = $this->getUser()->getCart();
        $cartItems = $cart->getCartItems();

        return $this->render('DurabolBundle:Default:productlist.html.twig', array(
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
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        
        return $this->render('DurabolBundle:Default:category.html.twig', array(
            'categories' => $categories,
            'shops' => $shops,
        ));
    }    
    
    public function ajaxUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $isAdd = $request->get('val1');
        $cartItemId = $request->get('val2');
        $repository = $this->getDoctrine()->getRepository('DurabolBundle:CartItem');
        $cartItem = $repository->find($cartItemId);
        $cartItem->setQuantity($cartItem->getQuantity()+$isAdd);
        $em->persist($cartItem);
        $em->flush();
        return new Response();
    }

    public function cartdeleteAction(CartItem $cartItem)
    {
        $cart = $this->getUser()->getCart();
        $cart->removeCartItem($cartItem);

        $em = $this->getDoctrine()->getManager();
        $em->remove($cartItem);
        $em->flush();

        return $this->redirectToRoute('durabol_homepage');
    }

    public function addtocartAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->getUser()->getCart();

        //获取ajax参数
        $num = $request->get('num');
        $productId = $request->get('id');
        //获取product实体
        $repository = $this->getDoctrine()->getRepository('DurabolBundle:Product');
        $product = $repository->find($productId);

        //新增购物车商品实体
        $newCartItem = new CartItem();
        if($product->getIsSale())
        {
            $price = ($product->getDiscountPrice()) * ($this->getUser()->getDiscount()) / 100;
        }else{
            $price = ($product->getPrice()) * ($this->getUser()->getDiscount()) / 100;
        }
        $newCartItem->setCart($cart)->setProduct($product)->setQuantity($num)->setPrice($price);
        $cart->addCartItem($newCartItem);
        $em->persist($newCartItem);
        $em->flush();
        return new Response();
    }

    public function guestinfoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        $user = $this->getUser();
        $data = $user->getData();

        return $this->render('DurabolBundle:Default:guestinfo.html.twig', array(
            'user' => $user,
            'data' => $data,
            'shops' => $shops,
        ));
    }

    public function pedidoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository('DurabolBundle:OrderInfo');
        $orderInfos = $repository->findByUser($user, array('orderDate' => 'DESC'));

        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        return $this->render('DurabolBundle:Default:pedido.html.twig', array(
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
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        return $this->render('DurabolBundle:Default:pedido.html.twig', array(
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
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        return $this->render('DurabolBundle:Default:productlistclient.html.twig', array(
            'orderItems' => $orderItems,
            'orderInfo' => $orderInfo,
            'user' => $user,
            'shops' => $shops,
        ));
    }

    public function infoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));

        return $this->render('DurabolBundle:Info:info.html.twig', array(
            'shops' => $shops,
        ));
    }
}
