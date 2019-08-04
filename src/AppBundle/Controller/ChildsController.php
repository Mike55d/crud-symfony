<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Childs;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
     * @Route("childs")
     */
class ChildsController extends Controller
{
    /**
     * @Route("/primeraVez" , name="childs_index")
     */
    public function indexAction( Request $request)
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
            return $this->render('AppBundle:Childs:index.html.twig', array(
                'childs' => $childs,
                'lista'=> 'first',
            ));
        }

    /**
     * @Route("/descartados" , name="childs_discard")
     */
    public function discardAction()
    {   
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>'discard','sede'=> $sede],['id'=> 'ASC']);  
        return $this->render('AppBundle:Childs:index.html.twig', array(
            'childs' => $childs,
            'lista'=> 'discard'
        ));
    }

    /**
     * @Route("/frecuentes" , name="childs_frequent")
     */
    public function frequentAction( Request $request)
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
            return $this->render('AppBundle:Childs:index.html.twig', array(
                'childs' => $childs,
                'lista'=> 'frequent',
            ));
        }

    /**
     * @Route("/new" , name="childs_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede();
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findAll();
        $institute = $em->getRepository('AppBundle:Institute')->findAll();
        $route = $em->getRepository('AppBundle:Ruta')->findAll();
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findAll();
        // datos autocompletar 
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>'first','sede'=> $sede]);
        foreach ($childs as $child) $childNames[]= $child->getName();
        foreach ($childs as $child) $childPhones[]= $child->getPhone();
        foreach ($childs as $child) $childEmails[]= $child->getEmail();
        foreach ($childs as $child) $childParents[]= $child->getParents();
        if ($request->get('name')) {
            $child = new Childs;
            $child->setName($request->get('name'));
            $child->setBirthday(new \DateTime($request->get('date')));
            //calcular edad de la fecha de nacimiento
            $cumpleaños = new \DateTime($request->get('date'));
            $hoy = new \DateTime();
            $edad = $hoy->diff($cumpleaños);
            $child->setAge($edad->y);
            $child->setPhone($request->get('phone'));
            $child->setEmail($request->get('email'));
            $child->setAddress($request->get('address'));
            $child->setBarrio($request->get('barrio'));
            $child->setParents($request->get('parents'));
            $child->setGrade($request->get('grade'));
            $child->setGrupo($em->getRepository('AppBundle:Grupo')
                ->find($request->get('grupo')));
            $child->setInstitute($em->getRepository('AppBundle:Institute')
                ->find($request->get('institute')));
            $child->setRoute($request->get('route'));
            $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
                ->find($request->get('telefonero')));
            $child->setObservations($request->get('observations') ?? 'ninguna');
            $child->setComments($request->get('comments') ?? 'ninguno');
            $child->setViernes($request->get('viernes'));
            $child->setSabado($request->get('sabado'));
            $child->setDomingo($request->get('domingo'));
            $child->setLat($request->get('lat'));
            $child->setLng($request->get('lng'));
            //insertar imagen
            if ($request->files->get('image')) {
               $file = $request->files->get('image');
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('childs'),$fileName);
               $child->setImage($fileName);
           }
           $child->setType('first');
           $child->setSede($sede);

           $em->persist($child);
           $em->flush();
           return $this->redirectToRoute('childs_index');
       }

       return $this->render('AppBundle:Childs:new.html.twig', array(
        'grupos' => $grupo,
        'institutes' => $institute,
        'routes' => $route,
        'telefoneros' => $telefoneros,
        'childNames'=>$childNames,
        'childPhones'=>$childPhones,
        'childEmails'=>$childEmails,
        'childParents'=>$childParents
    ));
   }

    /**
     * @Route("/{lista}/{id}/edit" , name="childs_edit")
     */
    public function editAction(Request $request , Childs $child ,$lista )
    {
        $em =$this->getDoctrine()->getManager(); 
        $user= $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $anterior = $_SERVER['HTTP_REFERER'];
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findAll();
        $institute = $em->getRepository('AppBundle:Institute')->findAll();
        $route = $em->getRepository('AppBundle:Ruta')->findAll();
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findAll();
        $telefonero = null;
        if ($user->getRola() == 'USER') {
            $telefonero = $user->getTelefonero();
        }
        $next = $em->getRepository('AppBundle:Childs')
        ->nextId($child->getId(),$sede,$lista,$telefonero);
        $back = $em->getRepository('AppBundle:Childs')
        ->backId($child->getId(),$sede,$lista,$telefonero);
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>'first','sede'=> $sede]);
        // datos autocompletar 
        foreach ($childs as $Child) $childNames[]= $Child->getName();
        foreach ($childs as $Child) $childPhones[]= $Child->getPhone();
        foreach ($childs as $Child) $childEmails[]= $Child->getEmail();
        foreach ($childs as $Child) $childParents[]= $Child->getParents();

        if ($request->get('name')) {
            $child->setName($request->get('name'));
            $child->setBirthday(new \DateTime($request->get('date')));
             //calcular edad de la fecha de nacimiento
            $cumpleaños = new \DateTime($request->get('date'));
            $hoy = new \DateTime();
            $edad = $hoy->diff($cumpleaños);
            $child->setAge($edad->y);
            $child->setPhone($request->get('phone'));
            $child->setEmail($request->get('email'));
            $child->setAddress($request->get('address'));
            $child->setBarrio($request->get('barrio'));
            $child->setParents($request->get('parents'));
            $child->setGrade($request->get('grade'));
            $child->setGrupo($em->getRepository('AppBundle:Grupo')
                ->find($request->get('grupo')));
            $child->setInstitute($em->getRepository('AppBundle:Institute')
                ->find($request->get('institute')));
            $child->setRoute($request->get('route'));
            $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
                ->find($request->get('telefonero')));
            $child->setObservations($request->get('observations'));
            $child->setComments($request->get('comments'));
            $child->setViernes($request->get('viernes'));
            $child->setSabado($request->get('sabado'));
            $child->setDomingo($request->get('domingo'));
            $child->setLat($request->get('lat'));
            $child->setLng($request->get('lng'));
            //insertar imagen
            if ($request->files->get('image')) {
               $file = $request->files->get('image');
               $fileName = md5(uniqid()).'.'.$file->guessExtension();
               $file->move($this->getParameter('childs'),$fileName);
               $child->setImage($fileName);
           }
           $em->persist($child);
           $em->flush();
           return $this->redirectToRoute('childs_edit',
            ['lista'=>$lista, 'id'=> $child->getId()]);
       }
       return $this->render('AppBundle:Childs:edit.html.twig', array(
        'child'=> $child,
        'grupos' => $grupo,
        'institutes' => $institute,
        'routes' => $route,
        'telefoneros' => $telefoneros,
        'next'=>$next,
        'back'=>$back,
        'lista'=> $lista,
        'childNames'=>$childNames,
        'childPhones'=>$childPhones,
        'childEmails'=>$childEmails,
        'childParents'=>$childParents,
        'anterior'=> $anterior
    ));
   }

    /**
     * @Route("/{id}/del" , name="childs_del")
     */
    public function delAction(Childs $child)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();
        return $this->redirectToRoute('childs_index');
    }

 /**
     * @Route("/clean" , name="childs_clean")
     */
    public function cleanAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede(); 
        $childsFirst = $em->getRepository('AppBundle:Childs')
        ->findBy(['sede'=> $sede , 'type'=> 'first']);
        $childsFrequent = $em->getRepository('AppBundle:Childs')
        ->findBy(['sede'=> $sede , 'type'=> 'frequent']);  
        foreach ($childsFirst as $child){
            $child->setViernes(null);
            $child->setSabado(null);
            $child->setDomingo(null);
        }
        foreach ($childsFrequent as $child){
            $child->setViernes(null);
            $child->setSabado(null);
            $child->setDomingo(null);
        }
        $this->addFlash('notice','Se han limpiado los registros');
        $em->flush();
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/update" , name="childs_update")
     */
    public function updateAction(Request $request){
        $em =$this->getDoctrine()->getManager(); 
        $child = $em->getRepository('AppBundle:Childs')
        ->find($request->get('id')); 
        $child->setName($request->get('name'));
        $child->setBirthday(new \DateTime($request->get('date')));
         //calcular edad de la fecha de nacimiento
        $cumpleaños = new \DateTime($request->get('date'));
        $hoy = new \DateTime();
        $edad = $hoy->diff($cumpleaños);
        $child->setAge($edad->y);
        $child->setPhone($request->get('phone'));
        $child->setEmail($request->get('email'));
        $child->setAddress($request->get('address'));
        $child->setBarrio($request->get('barrio'));
        $child->setParents($request->get('parents'));
        $child->setGrade($request->get('grade'));
        $child->setGrupo($em->getRepository('AppBundle:Grupo')
            ->find($request->get('grupo')));
        $child->setInstitute($em->getRepository('AppBundle:Institute')
            ->find($request->get('institute')));
        $child->setRoute($request->get('route'));
        $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
            ->find($request->get('telefonero')));
        $child->setObservations($request->get('observations'));
        $child->setComments($request->get('comments'));
        $child->setViernes($request->get('viernes'));
        $child->setSabado($request->get('sabado'));
        $child->setDomingo($request->get('domingo'));
        $child->setLat($request->get('lat'));
        $child->setLng($request->get('lng'));
        //insertar imagen
        if ($request->files->get('image')) {
           $file = $request->files->get('image');
           $fileName = md5(uniqid()).'.'.$file->guessExtension();
           $file->move($this->getParameter('childs'),$fileName);
           $child->setImage($fileName);
       }
        $em->flush();
        $name = $request->get('name');
        return new JsonResponse($name);
    }

}
