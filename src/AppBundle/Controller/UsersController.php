<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;
use AppBundle\Form\UsersMasterType;
use AppBundle\Form\UsersWebmasterType;
use AppBundle\Form\UsersEditType;
use AppBundle\Form\UsersEditProfileType;
use AppBundle\Form\UsersEditProfileMasterType;
use AppBundle\Entity\Telefonero;
use Symfony\Component\HttpFoundation\File\File;

 /**
     * @Route("users")
     */
 class UsersController extends Controller
 {
     /**
     * @Route("/" , name="users_index")
     */
     public function indexAction()
     {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $users = $em->getRepository('AppBundle:Users')->findBySede($sede); 
        return $this->render('AppBundle:Users:index.html.twig', array(
            'users'=> $users
        ));
    }

    /**
     * @Route("/users_webmaster" , name="users_index_master")
     */
    public function indexMasterAction()
    {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $users = $em->getRepository('AppBundle:Users')->findAll(); 
        return $this->render('AppBundle:Users:index.html.twig', array(
            'users'=> $users
        ));
    }

    /**
     * @Route("/new" , name="users_new")
     */
    public function newAction(Request $request)
    {
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede();
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $user->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('profiles'),$fileName);
            $user->setImage($fileName);
            $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setSede($sede);
            if ($user->getRoles()[0] == 'ROLE_USER') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'USER']); 
                $user->setPermisos($permisos);
                $user->setRola('USER');
            }
            if ($user->getRoles()[0] == 'ROLE_ADMIN') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'ADMIN']); 
                $user->setPermisos($permisos);
                $user->setRola('ADMIN');
            }
            if ($request->get('crearTelefonero')) {
                $telefonero = new Telefonero;
                $telefonero->setSede($sede);
                $telefonero->setName($user->getName()); 
                $telefonero->setPhone($user->getPhone()); 
                $telefonero->setImage($fileName); 
                $user->setTelefonero($telefonero);
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_index');
        }
        return $this->render('AppBundle:Users:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/new_master" , name="users_new_master")
     */
    public function newMasterAction(Request $request)
    {   
        $user = new Users();
        $form = $this->createForm(UsersMasterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $sede = $user->getSede();
            $file = $user->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('profiles'),$fileName);
            $user->setImage($fileName);
            $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            if ($user->getRoles()[0] == 'ROLE_USER') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'USER']); 
                $user->setPermisos($permisos);
                $user->setRola('USER');
            }
            if ($user->getRoles()[0] == 'ROLE_ADMIN') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'ADMIN']); 
                $user->setPermisos($permisos);
                $user->setRola('ADMIN');
            }
            if ($request->get('crearTelefonero')) {
                $telefonero = new Telefonero;
                $telefonero->setSede($sede);
                $telefonero->setName($user->getName()); 
                $telefonero->setPhone($user->getPhone()); 
                $telefonero->setImage($fileName); 
                $user->setTelefonero($telefonero);
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_index_master');
        }
        return $this->render('AppBundle:Users:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/{id}/edit" , name="users_edit")
     */
    public function editAction(Request $request, Users $user)
    {
        $em =$this->getDoctrine()->getManager(); 
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede();
        $form = $this->createForm(UsersEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($request->get('roles'));
            if ($user->getRoles()[0] == 'ROLE_USER') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'USER']); 
                $user->setPermisos($permisos);
                $user->setRola('USER');
            }
            if ($user->getRoles()[0] == 'ROLE_ADMIN') 
            {
                $permisos = $em->getRepository('AppBundle:Permisos')
                ->findOneBy(['sede'=>$sede,'type'=>'ADMIN']); 
                $user->setPermisos($permisos);
                $user->setRola('ADMIN');
            }
            $em->flush();
            return $this->redirectToRoute('users_index');
        }

        return $this->render('AppBundle:Users:edit.html.twig', array(
            'form'=> $form->createView(),
            'user'=> $user
        ));
    }

    /**
     * @Route("/{id}/del" , name="users_del")
     */
    public function delAction(Users $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/activateUsers" , name="users_activate")
     */
    public function activateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $users = $em->getRepository('AppBundle:Users')
        ->findBy(['sede'=>$sede,'rola'=>'USER']); 
        foreach ($users as $user) $user->setActive(1);
        $em->flush();
        $this->addFlash('notice','Usuarios Activados');
        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/disableUsers" , name="users_disable")
     */
    public function disableAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sede = $this->get('security.token_storage')
        ->getToken()->getUser()->getSede(); 
        $users = $em->getRepository('AppBundle:Users')
        ->findBy(['sede'=>$sede,'rola'=>'USER']); 
        foreach ($users as $user) $user->setActive(0);
        $em->flush();
        $this->addFlash('notice','Usuarios Desactivados');
        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/editProfile" , name="users_editProfile")
     */
    public function editProfileAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $form = $this->createForm(UsersEditProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
             //insertar imagen
            if ($request->get('image')) {
             $img = $request->get('image');
             $img = str_replace('data:image/jpeg;base64,', '', $img);
             $img = str_replace(' ', '+', $img);
             $data = base64_decode($img);
             $file = 'image.jpeg';
             $success = file_put_contents($file, $data);
             $file = new File($file);
             $fileName = md5(uniqid()).'.'.$file->guessExtension();
             $file->move($this->getParameter('profiles'),$fileName);
             $user->setImage($fileName);
         }
         $em->flush();
         return $this->redirectToRoute('homepage');
     }
     return $this->render('AppBundle:Users:editProfile.html.twig', array(
        'form'=> $form->createView(),
    ));
 }

     /**
     * @Route("/newWebmaster" , name="users_newWebmaster")
     */
     public function newWebmasterAction(Request $request)
     {
        $user = new Users();
        $form = $this->createForm(UsersWebmasterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $permisos = $em->getRepository('AppBundle:Permisos')
            ->findOneBy(['type'=>'WEBMASTER']); 
            $file = $user->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('profiles'),$fileName);
            $user->setImage($fileName);
            $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles("ROLE_SUPER_ADMIN"); 
            $user->setRola('WEBMASTER');
            $user->setPermisos($permisos);         
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_index');
        }
        return $this->render('AppBundle:Users:newWebmaster.html.twig', array(
            'form'=> $form->createView()
        ));
    }

     /**
     * @Route("/{id}/editProfile_master" , name="users_editProfile_master")
     */
     public function editProfileMasterAction(Request $request , Users $user)
     {
        $em =$this->getDoctrine()->getManager(); 
        $form = $this->createForm(UsersEditProfileMasterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
             //insertar imagen
            if ($request->files->get('image')) {
             $file = $request->files->get('image');
             $fileName = md5(uniqid()).'.'.$file->guessExtension();
             $file->move($this->getParameter('profiles'),$fileName);
             $user->setImage($fileName);
         }
         $user->setRoles($request->get('roles'));
         if ($user->getRoles()[0] == 'ROLE_USER') 
         {
            $permisos = $em->getRepository('AppBundle:Permisos')
            ->findOneBy(['sede'=>$user->getSede(),'type'=>'USER']); 
            $user->setPermisos($permisos);
            $user->setRola('USER');
        }
        if ($user->getRoles()[0] == 'ROLE_ADMIN') 
        {
            $permisos = $em->getRepository('AppBundle:Permisos')
            ->findOneBy(['sede'=>$user->getSede(),'type'=>'ADMIN']); 
            $user->setPermisos($permisos);
            $user->setRola('ADMIN');
        }
        $em->flush();
        return $this->redirectToRoute('users_index_master');
    }
    return $this->render('AppBundle:Users:editMaster.html.twig', array(
        'form'=> $form->createView(),
        'user'=> $user
    ));
}

}
