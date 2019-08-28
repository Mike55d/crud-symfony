<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
     * @Route("permisos")
     */
class PermisosController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {

        return $this->render('AppBundle:Permisos:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/new")
     */
    public function newAction()
    {
        return $this->render('AppBundle:Permisos:new.html.twig', array(
            // ...
        ));
    }

}
