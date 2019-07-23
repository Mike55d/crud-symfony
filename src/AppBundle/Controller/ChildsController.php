<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Childs;


/**
     * @Route("childs")
     */
class ChildsController extends Controller
{
    /**
     * @Route("/primeraVez" , name="childs_index")
     */
    public function indexAction()
    {   
        $em =$this->getDoctrine()->getManager();
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>'first','sede'=> $sede]); 
        return $this->render('AppBundle:Childs:index.html.twig', array(
            'childs' => $childs
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
        ->findBy(['type'=>'discard','sede'=> $sede]);  
        return $this->render('AppBundle:Childs:index.html.twig', array(
            'childs' => $childs
        ));
    }

    /**
     * @Route("/new" , name="childs_new")
     */
    public function newAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        //calcular edad
        $cumpleanos = new \DateTime("1996-08-23");
        $hoy = new \DateTime();
        $edad = $hoy->diff($cumpleanos);
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findAll();
        $institute = $em->getRepository('AppBundle:Institute')->findAll();
        $route = $em->getRepository('AppBundle:Route')->findAll();
        $telefonero = $em->getRepository('AppBundle:Telefonero')->findAll();

        if ($request->get('name')) {
            $child = new Childs;
            $child->setName($request->get('name'));
            $child->setBirthday(new \DateTime($request->get('date')));
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
            $child->setRecojer($request->get('recojer'));
            $child->setConfirmar($request->get('confirmar'));
            $child->setLLega($request->get('llega'));
            $child->setNoViene($request->get('noViene'));
            $child->setLat($request->get('lat'));
            $child->setLng($request->get('lng'));
            //insertar imagen
            $file = $request->files->get('image');
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images'),$fileName);
            $child->setImage($fileName);

            $em->persist($child);
            $em->flush();
            return $this->redirectToRoute('childs_index');
        }

        return $this->render('AppBundle:Childs:new.html.twig', array(
            'grupos' => $grupo,
            'institutes' => $institute,
            'routes' => $route,
            'telefoneros' => $telefonero
        ));
    }

    /**
     * @Route("/{id}/edit" , name="childs_edit")
     */
    public function editAction(Request $request , Childs $child )
    {
        $em =$this->getDoctrine()->getManager(); 
        //calcular edad
        $cumpleanos = new \DateTime("1996-08-23");
        $hoy = new \DateTime();
        $edad = $hoy->diff($cumpleanos);
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findAll();
        $institute = $em->getRepository('AppBundle:Institute')->findAll();
        $route = $em->getRepository('AppBundle:Route')->findAll();
        $telefonero = $em->getRepository('AppBundle:Telefonero')->findAll();

        if ($request->get('name')) {
            $child->setName($request->get('name'));
            $child->setBirthday(new \DateTime($request->get('date')));
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
            $child->setRecojer($request->get('recojer'));
            $child->setConfirmar($request->get('confirmar'));
            $child->setLLega($request->get('llega'));
            $child->setNoViene($request->get('noViene'));
            $child->setLat($request->get('lat'));
            $child->setLng($request->get('lng'));
            //insertar imagen
            $file = $request->files->get('image');
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('images'),$fileName);
            $child->setImage($fileName);

            $em->persist($child);
            $em->flush();
            return $this->redirectToRoute('childs_index');
        }
        return $this->render('AppBundle:Childs:edit.html.twig', array(
            'grupos' => $grupo,
            'institutes' => $institute,
            'routes' => $route,
            'telefoneros' => $telefonero,
            'child'=> $child
        ));
    }

    /**
     * @Route("/{id}/del" , name="childs_del")
     */
    public function delAction(Childs $child)
    {
        return $this->render('AppBundle:Childs:del.html.twig', array(
            // ...
        ));
    }

}
