<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
	* @Route("Cumples")
	*/
class ProximosCumplesController extends Controller
{
	/**
	* @Route("/" , name="proximosCumples")
	*/
	public function indexAction()
	{
		$em =$this->getDoctrine()->getManager();
		$user = $this->get('security.token_storage')
		->getToken()->getUser();
		$sede = $user->getSede(); 
		$cumples = [];
		$childs = $em->getRepository('AppBundle:Childs')->findBy(['sede'=>$sede ,'type'=>'first' ]); 
		$childs2 = $em->getRepository('AppBundle:Childs')->findBy(['sede'=>$sede ,'type'=>'frequent' ]);
		foreach ($childs as $child) {
			$date = $child->getBirthday();
			$mes = $date->format('m'); //month
			$dia = $date->format('d');
			$newDate = date('Y').'-'.$mes.'-'.$dia;
			array_push($cumples,[
				'title'=>$child->getName(),
				'start'=>$newDate,
			]);
		}
		foreach ($childs2 as $child) {
			$date = $child->getBirthday();
			$mes = $date->format('m'); //month
			$dia = $date->format('d');
			$newDate = date('Y').'-'.$mes.'-'.$dia;
			array_push($cumples,[
				'title'=>$child->getName(),
				'start'=> $newDate,
			]);
		}
		return $this->render('AppBundle:ProximosCumples:index.html.twig', array(
			'cumples'=>$cumples
		));
	}

}
