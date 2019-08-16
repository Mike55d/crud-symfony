<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\StarsChilds;

/**
     * @Route("starsChilds")
     */
class EstrellasController extends Controller
{
    /**
     * @Route("/addStar" , name="addStar")
     */
    public function indexAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $star = $em->getRepository('AppBundle:Estrella')
        ->find($request->get('star'));
        $child = $em->getRepository('AppBundle:Childs')
        ->find($request->get('child'));  
        $starChild = new StarsChilds;
        $starChild->setStar($star);
        $starChild->setChild($child);
        $starChild->setDate(new \DateTime());
        $em->persist($starChild);
        $em->flush();
        return new JsonResponse(1);
    }

    /**
     * @Route("/delStar" , name="delStar")
     */
    public function nexAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $star = $em->getRepository('AppBundle:StarsChilds')
        ->find($request->get('id')); 
        $em->remove($star);
        $em->flush();                   
        return new JsonResponse(1);
    }

}
