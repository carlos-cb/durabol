<?php

namespace DurabolBundle\Controller;

use Stripe\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DurabolBundle\Entity\OrderInfo;
use DurabolBundle\Entity\OrderItem;
use DurabolBundle\Entity\Data;

class OrderController extends Controller
{
    public function cartToOrderinfoAction(Request $request)
    {
        $priceAll = $this->countAll();
        $cartShops = $this->getCartShops();
        $userDiscount = $this->getUser()->getDiscount() / 100;
        $priceIni = $priceAll / $userDiscount;
        $payType = '在线支付';
        $gerenshui = false;
        if($request->get('gerenshui') == '1'){
            $gerenshui = true;
            $priceAll = $priceAll * 1.052;
        }
        if($request->get('radio-group') == '2')
        {
            $payType = '银行转账';
        }
        //根据用户填写的表格新建订单
        if($request->getMethod() == 'POST' && ($priceIni!=0) ){
            //订单信息录入
            $orderInfo = new OrderInfo();
            $orderInfo->setUser($this->getUser())->setDiscount($userDiscount)->setOrderDate(new \DateTime('now'))->setGoodsFee(round($priceIni, 2))->setPayType($payType)
                ->setTotalPrice(round($priceAll, 2))->setReceiverName($request->get('name'))->setReceiverShuihao($request->get('shuihao'))->setReceiverPhone($request->get('phonenumber'))
                ->setReceiverAdress($request->get('address'))->setReceiverCity($request->get('city'))->setReceiverProvince($request->get('province'))
                ->setReceiverPostcode($request->get('postcode'))->setIsPayed(false)->setIsSended(false)->setIsOver(false)->setState("未付款")->setGerenshui($gerenshui);

            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('DurabolBundle:Data');
            $existeData = $repository->findOneByUser($this->getUser());

            if($existeData){
                $existeData
                    ->setReceivername($request->get('name'))
                    ->setReceiverphone($request->get('phonenumber'))
                    ->setReceiveradress($request->get('address'))
                    ->setReceivercity($request->get('city'))
                    ->setReceiverprovince($request->get('province'))
                    ->setReceiverpostcode($request->get('postcode'))
                    ->setReceivershuihao($request->get('shuihao'))
                    ->setReceivergerenshui($request->get('gerenshui'));
                $em->persist($existeData);
            }else{
                $data = new Data();
                $data->setUser($this->getUser())
                    ->setReceivername($request->get('name'))
                    ->setReceiverphone($request->get('phonenumber'))
                    ->setReceiveradress($request->get('address'))
                    ->setReceivercity($request->get('city'))
                    ->setReceiverprovince($request->get('province'))
                    ->setReceiverpostcode($request->get('postcode'))
                    ->setReceivershuihao($request->get('shuihao'))
                    ->setReceivergerenshui($request->get('gerenshui'));
                $em->persist($data);
            }

            $em->persist($orderInfo);
            $em->flush();

            //将购物车商品全部导入订单中
            $cleanCart = $this->itemToOrder($orderInfo);

            //根据不同的店铺生成子订单
            $this->setChildOrder($cartShops, $orderInfo);

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

    private function getCartShops()
    {
        $user = $this->getUser();
        $cartItems = $user->getCart()->getCartItems();
        $cartShops = array();
        $i = 0;

        foreach($cartItems as $cartItem)
        {
            $cartShops[$i] = $cartItem->getProduct()->getCategory()->getShop();
            $i ++;
            $cartShops = array_unique($cartShops);
        }
        return $cartShops;
    }

    private function setChildOrder($cartShops, OrderInfo $orderInfo)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($cartShops as $cartShop)
        {
            $childOrderInfo = clone $orderInfo;
            $childOrderInfo->setParent($orderInfo);
            $em->persist($childOrderInfo);
            $orderItems = $orderInfo->getOrderItems();
            $total = 0;
            foreach ($orderItems as $orderItem)
            {
                $orderItemShop = $orderItem->getProduct()->getCategory()->getShop();
                if($cartShop == $orderItemShop)
                {
                    $orderItem->setOrderInfo($childOrderInfo);
                    $childOrderInfo->addOrderItem($orderItem);
                    $price = $orderItem->getPrice();
                    $quantity = $orderItem->getQuantity();
                    $unit = $orderItem->getProduct()->getUnit();
                    $total += ($price * $quantity * $unit);
                }
            }
            $userDiscount = $this->getUser()->getDiscount() / 100;
            $totalIni = $total / $userDiscount;
            if($orderInfo->getGerenshui())
            {
                $total = $total * 1.052;
            }
            $childOrderInfo->setTotalPrice($total)->setGoodsFee($totalIni);
            $em->persist($childOrderInfo);
            $em->flush();
        }
    }

    public function orderpayAction(OrderInfo $orderInfo)
    {
        $user = $this->getUser();

        $cart = $this->getUser()->getCart();
        $cartItems = $cart->getCartItems();
        $childOrderInfos = $orderInfo->getChildren();

        return $this->render('DurabolBundle:Payment:orderpay.html.twig', array(
            'orderInfo' => $orderInfo,
            'cartItems' => $cartItems,
            'user' => $user,
            'childOrderInfos' => $childOrderInfos,
        ));
    }
}
