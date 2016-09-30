<?php

namespace LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/location")
     */
    public function indexAction()
    {
        return $this->render('LocationBundle:Default:index.html.twig');
    }
}
