<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller
{
    /**
     * @Route("/" , name="homepage" )
     */
    public function indexAction()
    {
    	$em =$this->getDoctrine()->getManager();
    	$user = $this->get('security.token_storage')
    	->getToken()->getUser();
    	$sede = $user->getSede(); 
    	$query = ['type'=>'first','sede'=> $sede];
    	$childs = $em->getRepository('AppBundle:Childs')->listaHome(null,$sede); 
    	if ($user->getRola() == 'USER') 
    	{
    		$childs = $em->getRepository('AppBundle:Childs')
    		->listaHome($user->getTelefonero(),$sede);
    		}
    		return $this->render('AppBundle:Home:index.html.twig', array(
    			'childs' => $childs,
    		));
    	}

    }
