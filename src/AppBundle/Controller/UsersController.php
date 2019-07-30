<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;
use AppBundle\Form\UsersEditType;

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
            if ($user->getRoles()[0] == 'ROLE_USER') $user->setRola('USER');
            if ($user->getRoles()[0] == 'ROLE_ADMIN') $user->setRola('ADMIN');
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_index');
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
        $form = $this->createForm(UsersEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($request->get('roles'));
            if ($request->get('roles') == 'ROLE_USER') $user->setRola('USER');
            if ($request->get('roles') == 'ROLE_ADMIN') $user->setRola('ADMIN');
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
        $users = $em->getRepository('AppBundle:Users')->findAll(); 
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
        $users = $em->getRepository('AppBundle:Users')->findByRola('USER'); 
        foreach ($users as $user) $user->setActive(0);
        $em->flush();
        $this->addFlash('notice','Usuarios Desactivados');
        return $this->redirectToRoute('users_index');
    }

}
