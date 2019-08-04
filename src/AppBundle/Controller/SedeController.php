<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Sede;
use AppBundle\Form\SedeType;

/**
     * @Route("sedes")
     */
class SedeController extends Controller
{
    /**
     * @Route("/" , name="sedes_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $sedes = $em->getRepository('AppBundle:Sede')->findAll(); 
        return $this->render('AppBundle:Sede:index.html.twig', array(
            'sedes'=> $sedes
        ));
    }

    /**
     * @Route("/new" , name="sedes_new")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $sede = new Sede();
        $form = $this->createForm(SedeType::class, $sede);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sede);
            $em->flush();
            return $this->redirectToRoute('sedes_index');
        }
        return $this->render('AppBundle:Sede:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit" , name="sedes_edit")
     */
    public function editAction(Request $request, Sede $sede)
    {
        $em =$this->getDoctrine()->getManager(); 
        $form = $this->createForm(SedeType::class, $sede);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('sedes_index');
        }

        return $this->render('AppBundle:Sede:edit.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/del" , name="sedes_del")
     */
    public function delAction(Sede $sede)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($sede);
        $em->flush();
        return $this->redirectToRoute('sedes_index');
    }

}
