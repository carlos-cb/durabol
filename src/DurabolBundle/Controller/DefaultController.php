<?php

namespace DurabolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DurabolBundle:Default:index.html.twig');
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

    public function categoryAction()
    {
        return $this->render('DurabolBundle:Default:category.html.twig');
    }

    public function productlistAction()
    {
        return $this->render('DurabolBundle:Default:productlist.html.twig');
    }
}
