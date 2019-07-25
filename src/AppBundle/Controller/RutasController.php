<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ruta;
use AppBundle\Form\RutaType;


/**
     * @Route("Rutas")
     */
class RutasController extends Controller
{
    /**
     * @Route("/" , name="rutas_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $rutas = $em->getRepository('AppBundle:Ruta')->findBySede($sede); 
        return $this->render('AppBundle:Routes:index.html.twig', array(
            'rutas'=> $rutas
        ));
    }

    /**
     * @Route("/new" , name="rutas_new")
     */
    public function newAction(Request $request)
    {
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede();
        $ruta = new Ruta();
        $form = $this->createForm(RutaType::class, $ruta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ruta->setSede($sede);
            $em->persist($ruta);
            $em->flush();
            return $this->redirectToRoute('rutas_index');
        }
        return $this->render('AppBundle:Routes:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit" , name="rutas_edit")
     */
    public function editAction(Request $request, Ruta $ruta)
    {
        $em =$this->getDoctrine()->getManager(); 
        $form = $this->createForm(RutaType::class, $ruta);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('rutas_index');
        }

        return $this->render('AppBundle:Routes:edit.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/del" , name="rutas_del")
     */
    public function delAction(Ruta $ruta)
    {
        return $this->render('AppBundle:Routes:del.html.twig', array(
            // ...
        ));
    }

}
