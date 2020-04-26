<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Sede;
use AppBundle\Entity\Permisos;
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
            $permisosUser = new Permisos;
            $permisosUser->setSede($sede);
            $permisosUser->setType('USER');
            $permisosUser->setPermisos([]);
            $permisosAdmin = new Permisos;
            $permisosAdmin->setSede($sede);
            $permisosAdmin->setType('ADMIN');
            $permisosAdmin->setPermisos([]);
            $em->persist($permisosUser);
            $em->persist($permisosAdmin);
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
        $user = $em->getRepository('AppBundle:Users')
        ->findBySede($sede->getId()); 
        $child = $em->getRepository('AppBundle:Childs')
        ->findBySede($sede->getId()); 
        if ($user) {
            $this->addFlash('notice','Hy usuarios registrados con esta sede');
            return $this->redirectToRoute('sedes_index');
        }
        if ($child) {
            $this->addFlash('notice','Hay registros con esta sede ');
            return $this->redirectToRoute('sedes_index');
        }
        $em->remove($sede);
        $em->flush();
        return $this->redirectToRoute('sedes_index');
    }

    /**
     * @Route("/{id}/permisos" , name="sedes_permisos")
     */
    public function permisosAction(Request $request, Sede $sede)
    {
        $em =$this->getDoctrine()->getManager();
        $permisosUser = $em->getRepository('AppBundle:Permisos')
        ->findOneBy(['sede'=>$sede->getId(),'type'=>'USER']); 
        $permisosAdmin = $em->getRepository('AppBundle:Permisos')
        ->findOneBy(['sede'=>$sede->getId(),'type'=>'ADMIN']); 
        if ($request->get('permisosUser') || $request->get('permisosAdmin') ) {
            $permisosUser->setPermisos($request->get('permisosUser'));
            $permisosAdmin->setPermisos($request->get('permisosAdmin'));
        $em->flush();
        }
        return $this->render('AppBundle:Sede:permisos.html.twig', array(
            'sede'=>$sede,
            'permisosUser'=>$permisosUser,
            'permisosAdmin'=>$permisosAdmin,
        ));
    }

}
