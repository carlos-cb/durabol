<?php

namespace DurabolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DurabolBundle\Entity\OrderInfo;
use DurabolBundle\Entity\OrderItem;

class OrderController extends Controller
{
    public function cartToOrderinfoAction(Request $request)
    {
        $priceAll = $this->countAll();
        $userDiscount = $this->getUser()->getDiscount() / 100;
        $priceIni = $priceAll / $userDiscount;
        if($request->get('radio-group') == '1')
        {

        }
        //根据用户填写的表格新建订单
        if($request->getMethod() == 'POST' && ($priceIni!=0) ){
            //订单信息录入
            $orderInfo = new OrderInfo();
            $orderInfo->setUser($this->getUser())
                ->setDiscount($userDiscount)
                ->setOrderDate(new \DateTime('now'))
                ->setGoodsFee(round($priceIni, 2))
                ->setPayType('online')
                ->setTotalPrice(round($priceAll, 2))
                ->setReceiverName($request->get('name'))
                ->setReceiverShuihao($request->get('shuihao'))
                ->setReceiverPhone($request->get('phonenumber'))
                ->setReceiverAdress($request->get('address'))
                ->setReceiverCity($request->get('city'))
                ->setReceiverProvince($request->get('province'))
                ->setReceiverPostcode($request->get('postcode'))
                ->setIsPayed(false)
                ->setIsSended(false)
                ->setIsOver(false)
                ->setState("未付款");

            $em = $this->getDoctrine()->getManager();
            $em->persist($orderInfo);
            $em->flush();

            //将购物车商品全部导入订单中
            $cleanCart = $this->itemToOrder($orderInfo);

            //清空购物车的所有商品并且状态改为已生成订单
            $this->clearCarrito();

            $cart = $this->getUser()->getCart();
            $cart->setState('over');
            $em->flush();

        }else{
            return $this->redirectToRoute('durabol_homepage');
        }

        return $this->redirectToRoute('order_orderpay', array(
            'orderInfo' => $orderInfo,
        ));
    }

    private function itemToOrder(OrderInfo $orderInfo)
    {
        $user = $this->getUser();
        $cartItems = $user->getCart()->getCartItems();
        $em = $this->getDoctrine()->getManager();

        foreach($cartItems as $cartItem)
        {
            $orderItem = new OrderItem();
            $orderItem
                ->setQuantity($cartItem->getQuantity())
                ->setPrice($cartItem->getPrice())
                ->setOrderInfo($orderInfo)
                ->setProduct($cartItem->getProduct());

            $orderInfo->addOrderItem($orderItem);
            $em->persist($orderItem);
        }
        $em->flush();
    }

    public function clearCarrito()
    {
        $cart = $this->getUser()->getCart();
        $cartItems = $cart->getCartItems();

        $em = $this->getDoctrine()->getManager();
        foreach($cartItems as $cartItem){
            $cart->removeCartItem($cartItem);
            $em->remove($cartItem);
        }
        $em->flush();
    }

    private function countAll()
    {
        $user = $this->getUser();
        $cartItems = $user->getCart()->getCartItems();
        $priceall = 0;

        foreach($cartItems as $cartItem)
        {
            $priceall += ($cartItem->getQuantity() * $cartItem->getPrice() * $cartItem->getProduct()->getUnit());
        }
        return $priceall;
    }

    public function orderpayAction(OrderInfo $orderInfo)
    {
        $user = $this->getUser();

        $cart = $this->getUser()->getCart();
        $cartItems = $cart->getCartItems();

        return $this->render('DurabolBundle:Payment:orderpay.html.twig', array(
            'orderInfo' => $orderInfo,
            'cartItems' => $cartItems,
            'user' => $user,
        ));
    }
}
