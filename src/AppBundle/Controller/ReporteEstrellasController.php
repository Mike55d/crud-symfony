<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Spipu\Html2Pdf\Html2Pdf;

 /**
     * @Route("Puntos")
     */
class ReporteEstrellasController extends Controller
{
    /**
     * @Route("/" , name="resportes_puntos")
     */
    public function indexAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager(); 
        $grupos = $em->getRepository('AppBundle:Grupo')->findAll(); 
        if ($request->get('grupo')) {
           return $this->forward('AppBundle:ReporteEstrellas:reportePdf',
                ['grupo'=>$request->get('grupo'),
                'type'=>$request->get('type')]);
        }
        return $this->render('AppBundle:ReporteEstrellas:index.html.twig', array(
            'grupos'=>$grupos
        ));
    }

    /**
     * @Route("/new" , name="reportes_puntos_pdf")
     */
    public function reportePdfAction(Request $request, $grupo,$type)
    {   
        $em =$this->getDoctrine()->getManager(); 
        $childs = [];
        $html2pdf = new Html2Pdf();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $childsGrouptype = $em->getRepository('AppBundle:Childs')
        ->findBy(['sede'=>$sede,'grupo'=>$grupo,'type'=>$type]); 
        foreach ($childsGrouptype as $child) {
            $contador_positivos = 0;
            $contador_negativos = 0;
            $contador_bronce = 0;
            $contador_plata = 0;
            $contador_oro = 0;
            $contador_negra = 0;
        $bronces = $em->getRepository('AppBundle:StarsChilds')
        ->findBy(['child'=>$child->getId(),'star'=>1]);
        foreach ($bronces as $bronce) {
            $contador_bronce ++;
            $contador_positivos += intval($bronce->getStar()->getValor()); 
         }
         $platas = $em->getRepository('AppBundle:StarsChilds')
        ->findBy(['child'=>$child->getId(),'star'=>2]);
        foreach ($platas as $plata) {
            $contador_plata ++;
            $contador_positivos += intval($plata->getStar()->getValor()); 
         }
         $oros = $em->getRepository('AppBundle:StarsChilds')
        ->findBy(['child'=>$child->getId(),'star'=>3]);
        foreach ($oros as $oro) {
            $contador_oro ++;
            $contador_positivos += intval($oro->getStar()->getValor()); 
         } 
        $negras = $em->getRepository('AppBundle:StarsChilds')
        ->findBy(['child'=>$child->getId(),'star'=>4]);
        foreach ($negras as $negra) {
            $contador_negra ++;
            $contador_negativos += intval($negra->getStar()->getValor()); 
         } 
         array_push($childs,[
            'child'=> $child,
            'positivos'=> $contador_positivos,
            'negativos'=> $contador_negativos,
            'bronce'=> $contador_bronce,
            'plata'=>$contador_plata,
            'oro'=>$contador_oro,
            'negra'=>$contador_negra,
        ]);
        }
        $html2pdf->writeHTML($this->renderView('AppBundle:ReporteEstrellas:reporte.html.twig',[
              'childs'=> $childs,
          ]));

        $html2pdf->output('reportePuntos.pdf', 'D');
    }

}
