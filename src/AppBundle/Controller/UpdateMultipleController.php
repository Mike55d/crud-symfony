<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UpdateMultipleController extends Controller
{
    /**
     * @Route("/updateMUltiple", name="update_multiple")
     */
    public function updateMultipleAction(Request $request)
    {
    	$em =$this->getDoctrine()->getManager(); 
    	$type = $request->get('type');
    	$childs = $request->get('childs');
    	$editar = $request->get('editar');
    	$idEditar = $request->get('idEditar');
    	if ($editar == 'telefonero') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$telefonero = $em->getRepository('AppBundle:Telefonero')
    			->find($idEditar); 
    			$obChild->setTelefonero($telefonero);
    		}
    	}
    	if ($editar == 'sede') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$sede = $em->getRepository('AppBundle:Sede')
    			->find($idEditar); 
    			$obChild->setSede($sede);
    		}
    	}
    	if ($editar == 'grupo') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$grupo = $em->getRepository('AppBundle:Grupo')
    			->find($idEditar); 
    			$obChild->setGrupo($grupo);
    		}
    	}
    	if ($editar == 'tipo') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$obChild->setType($idEditar);
    		}
    	}
    	$em->flush();
    	return $this->redirectToRoute('childs_'.$type);
    }

  }
