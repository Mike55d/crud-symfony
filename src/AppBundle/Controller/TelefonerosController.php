<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
     * @Route("telefoneros" )
     */
class TelefonerosController extends Controller
{

     /**
     * @Route("/" , name="telefoneros_index")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Telefoneros:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/new" , name="telefoneros_new")
     */
    public function newAction()
    {
        return $this->render('AppBundle:Telefoneros:new.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Telefoneros:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/del")
     */
    public function delAction()
    {
        return $this->render('AppBundle:Telefoneros:del.html.twig', array(
            // ...
        ));
    }

}
