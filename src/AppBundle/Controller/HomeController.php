<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Childs;


class HomeController extends Controller
{
    /**
     * @Route("/" , name="homepage" )
     */
    public function indexAction()
    {
    	$em =$this->getDoctrine()->getManager();
    	$user = $this->get('security.token_storage')
    	->getToken()->getUser();
    	$sede = $user->getSede(); 
    	$query = ['type'=>'first','sede'=> $sede];
    	$childs = $em->getRepository('AppBundle:Childs')->listaHome(null,$sede); 
    	if ($user->getRola() == 'USER') 
    	{
    		$childs = $em->getRepository('AppBundle:Childs')
    		->listaHome($user->getTelefonero(),$sede);
    		}
    		return $this->render('AppBundle:Home:index.html.twig', array(
    			'childs' => $childs,
    		));
    	}

    /**
     * @Route("/{id}/del" , name="del_child")
     */
    public function delAction(Childs $child)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();
        $this->addFlash('notice','Registro removido satisfactoriamente');
        return $this->redirectToRoute('homepage');
    }

    }
