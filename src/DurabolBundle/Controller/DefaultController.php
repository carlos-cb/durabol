<?php

namespace DurabolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DurabolBundle:Default:index.html.twig');
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
