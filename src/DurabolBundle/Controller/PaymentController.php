<?php

namespace DurabolBundle\Controller;

use DurabolBundle\Entity\OrderInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    public function prepareStripeAction(OrderInfo $orderInfo)
    {
        $gatewayName = 'stripe_js';

        $storage = $this->get('payum')->getStorage('DurabolBundle\Entity\Payment');

        $payment = $storage->create();
        $payment->setNumber($orderInfo->getId());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount($orderInfo->getTotalPrice() * 100); // 1.23 EUR
        $payment->setClientId($orderInfo->getUser());

        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName,
            $payment,
            'payment_paymentDone' // the route to redirect after capture
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function doneAction(Request $request)
    {
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));
        $payment = $status->getFirstModel();

        $paymentStatus = false;
        if ($token->getGatewayName() === 'stripe_js'&& $status->isCaptured())
        {
            if ($status->getValue() === 'captured')
            {
                $paymentStatus = true;
            }
        }

        $em = $this->getDoctrine()->getManager();
        $shops = $em->getRepository('DurabolBundle:Shop')->findBy(array(), array('isTop' => 'DESC'));
        $user = $this->getUser();
        $orderInfoId = $payment->getNumber();
        $orderInfo = $em->getRepository('DurabolBundle:OrderInfo')->findOneById($orderInfoId);
        $childOrderInfos = $orderInfo->getChildren();

        if ($paymentStatus === true)
        {
            $messageClient = \Swift_Message::newInstance()
                ->setSubject('您在百团网的新订单已完成')
                ->setFrom(array('info@groupon100.com' => '百团网'))
                ->setTo($user->getEmail())
                ->setContentType('text/html')
                ->setBody(
                    $this->renderView(
                        'DurabolBundle:Payment:successpaymentEmail.html.twig', array(
                        'status' => $status->getValue(),
                        'childOrderInfos' => $childOrderInfos,
                        'orderInfo' => $orderInfo,
                        'userNow' => $user,
                        'shops' => $shops,
                    )),
                    'text/html'
                );
            $this->get('mailer')->send($messageClient);

            $messageBackend = \Swift_Message::newInstance()
                ->setSubject('百团网新订单提示')
                ->setFrom(array('info@groupon100.com' => '百团网'))
                ->setTo('1878118@qq.com')
                ->setContentType('text/html')
                ->setBody(
                    $this->renderView(
                        'DurabolBundle:Payment:successpaymentEmailBackend.html.twig', array(
                        'status' => $status->getValue(),
                        'orderInfo' => $orderInfo,
                        'childOrderInfos' => $childOrderInfos,
                        'userNow' => $user,
                        'shops' => $shops,
                    )),
                    'text/html'
                );
            $this->get('mailer')->send($messageBackend);

            //修改订单状态
            $orderInfo->setIsPayed(true)->setState('已付款');
            foreach ($childOrderInfos as $childOrderInfo)
            {
                $childOrderInfo->setIsPayed(true)->setState('已付款');
                $em->persist($childOrderInfo);
            }
            $em->persist($orderInfo);
            $em->flush();

            return $this->render('DurabolBundle:Payment:successpayment.html.twig', array(
                'status' => $status->getValue(),
                'orderInfo' => $orderInfo,
                'childOrderInfos' => $childOrderInfos,
                'userNow' => $user,
                'shops' => $shops,
            ));
        } else {
            return $this->render('DurabolBundle:Payment:errorpayment.html.twig', array(
                'orderInfo' => $orderInfo,
                'childOrderInfos' => $childOrderInfos,
                'shops' => $shops,
            ));
        }
    }
}
