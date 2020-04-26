<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
     * @Route("/listadoFotos")
     */
class ListadoFotosController extends Controller
{
    /**
     * @Route("/PrimeraVez" , name="listadoFotos_first")
     */
    public function PrimeraVezAction()
    {
        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede(); 
         $query = ['type'=>'first','sede'=> $sede];
        if ($user->getRola() == 'USER') 
        {
            $query = [
                'type'=>'first',
                'telefonero'=>$user->getTelefonero()];
            }
            $childs = $em->getRepository('AppBundle:Childs')
            ->findBy($query,['id'=> 'ASC']); 
        return $this->render('AppBundle:ListadoFotos:primera_vez.html.twig', array(
            'childs'=>$childs
        ));
    }

    /**
     * @Route("/Registros" , name="listadoFotos_frequent")
     */
    public function RegistrosAction()
    {
        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede(); 
        $query = ['type'=>'frequent','sede'=> $sede];
        if ($user->getRola() == 'USER') 
        {
            $query = [
                'type'=>'frequent',
                'telefonero'=>$user->getTelefonero()];
            }
            $childs = $em->getRepository('AppBundle:Childs')
            ->findBy($query,['id'=> 'ASC']); 
        return $this->render('AppBundle:ListadoFotos:registros.html.twig', array(
            'childs'=>$childs
        ));
    }

}
