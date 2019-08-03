<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Telefonero;
use Spipu\Html2Pdf\Html2Pdf;



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
        $em =$this->getDoctrine()->getManager();
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findBySede($sede); 
        return $this->render('AppBundle:Telefoneros:index.html.twig', array(
            'telefoneros'=>$telefoneros
        ));
    }

    /**
     * @Route("/new" , name="telefoneros_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        if ($request->get('name')) {
           $telefonero = new Telefonero;
           $telefonero->setName($request->get('name')); 
           $telefonero->setPhone($request->get('phone')); 
           $telefonero->setSede($sede);
        //insertar imagen
           if ($request->files->get('image')) {
               $file = $request->files->get('image');
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('telefoneros'),$fileName);
               $telefonero->setImage($fileName);
           }
           $em->persist($telefonero);
           $em->flush();
           return $this->redirectToRoute('telefoneros_index');
       }
       return $this->render('AppBundle:Telefoneros:new.html.twig', array(
            // ...
       ));
   }

    /**
     * @Route("/{id}/edit" , name="telefoneros_edit")
     */
    public function editAction(Request $request , Telefonero $telefonero)
    {
       $em =$this->getDoctrine()->getManager();
       $sede = $this->get('security.token_storage')
       ->getToken()->getUser()->getSede(); 
       if ($request->get('name')) {
           $telefonero->setName($request->get('name')); 
           $telefonero->setPhone($request->get('phone')); 
           $telefonero->setSede($sede);
        //insertar imagen
           if ($request->files->get('image')) {
               $file = $request->files->get('image');
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('telefoneros'),$fileName);
               $telefonero->setImage($fileName);
           }
           $em->persist($telefonero);
           $em->flush();
           return $this->redirectToRoute('telefoneros_index');
       }
       return $this->render('AppBundle:Telefoneros:edit.html.twig', array(
           'telefonero'=>$telefonero
       ));
   }

    /**
     * @Route("/{id}/del" , name="telefoneros_del")
     */
    public function delAction(Telefonero $telefonero)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($telefonero);
        $em->flush();
        return $this->redirectToRoute('telefoneros_index');
    }

     /**
     * @Route("/sePw" , name="setPw")
     */
    public function setPwAction()
    {
        $em = $this->getDoctrine()->getManager();
        $childs = $em->getRepository('AppBundle:Childs')->findAll(); 
        foreach ($childs as $child) {
          if (!$child->getType()) {
           $child->setType('frequent');
          }
        }
        $em->flush();
        return $this->redirectToRoute('telefoneros_index');
    }

/**
     * @Route("/test" , name="test")
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($this->renderView('AppBundle:Telefoneros:test.html.twig',[
          'user'=> 'blue',
        ]));
        $html2pdf->output();
    }
}
