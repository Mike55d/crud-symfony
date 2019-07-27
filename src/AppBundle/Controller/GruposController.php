<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Grupo;
use AppBundle\Form\GrupoType;

/**
     * @Route("grupos")
     */
class GruposController extends Controller
{
    /**
     * @Route("/" , name="grupos_index")
     */
    public function indexAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $grupos = $em->getRepository('AppBundle:Grupo')->findBySede($sede); 
        return $this->render('AppBundle:Grupos:index.html.twig', array(
            'grupos'=> $grupos
        ));
    }

    /**
     * @Route("/new" , name="grupos_new")
     */
    public function newAction(Request $request)
    {
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede();
        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $grupo->setSede($sede);
            $em->persist($grupo);
            $em->flush();
            return $this->redirectToRoute('grupos_index');
        }
        return $this->render('AppBundle:Grupos:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit" , name="grupos_edit")
     */
    public function editAction(Request $request, Grupo $grupo)
    {
        $em =$this->getDoctrine()->getManager(); 
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('grupos_index');
        }

        return $this->render('AppBundle:Grupos:edit.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/del" , name="grupos_del")
     */
    public function delAction(Grupo $grupo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($grupo);
        $em->flush();
        return $this->redirectToRoute('grupos_index');
    }

}
