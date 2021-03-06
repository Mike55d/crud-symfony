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
      $permisos = $em->getRepository('AppBundle:Permisos')->findOneBy(['sede'=>7,'type'=>'USER']); 
      $users = $em->getRepository('AppBundle:Users')->findBy(['sede'=>7]); 
      foreach ($users as $user) {
        $user->setRola('USER');
        $user->setRoles("ROLE_USER");
        $user->setPermisos($permisos);
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
  $user = $this->get('security.token_storage')
  ->getToken()->getUser();
  $sede = $user->getSede();
    /*
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($this->renderView('AppBundle:Telefoneros:test.html.twig',[
      'user'=> 'blue',
    ]));
    $html2pdf->output();
    */
    $data = [];
    $rutas = $em->getRepository('AppBundle:Ruta')->findAll();
    foreach ($rutas as $i => $route) {
      $childs = $em->getRepository('AppBundle:Childs')
      ->buscarRuta($route,'viernes',$sede,'first');
      $data[]=['ruta'=>$route,'childs'=>$childs];
    }
    return $this->render('AppBundle:Telefoneros:test2.html.twig',['data'=>$data]);
  }
}
