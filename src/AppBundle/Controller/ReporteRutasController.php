<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Spipu\Html2Pdf\Html2Pdf;


/**
     * @Route("/reporteRutas")
     */
class ReporteRutasController extends Controller
{
    /**
     * @Route("/" , name="resportes_rutas")
     */
    public function indexAction(Request $request )
    {	
      $em =$this->getDoctrine()->getManager(); 
      $user = $this->get('security.token_storage')
      ->getToken()->getUser();
      $sede = $user->getSede();
      $rutas=$em->getRepository('AppBundle:Ruta')->findBySede($sede); 
      if ($request->get('formato') == 'rtf') {
          if ($request->get('ruta') && $request->get('ruta') != 'todas') {
            return $this->forward('AppBundle:ReporteRutas:reportesRutasWord',
                ['dia'=>$request->get('dia'),
                'ruta'=>$request->get('ruta'),
                'type'=>$request->get('type')]);
        }
        if ($request->get('ruta') && $request->get('ruta') == 'todas') {
            return $this->forward('AppBundle:ReporteRutas:reportesAllRutasWord',
                ['dia'=>$request->get('dia'),
                'ruta'=>$request->get('ruta'),
                'type'=>$request->get('type')]);
        }
    }
    if ($request->get('formato') == 'pdf') {
        return $this->forward('AppBundle:ReporteRutas:reportesRutasPDF',
            ['dia'=>$request->get('dia'),
            'ruta'=>$request->get('ruta'),
            'type'=>$request->get('type')]);
    }
    return $this->render('AppBundle:ReporteRutas:index.html.twig', array(
        'rutas'=> $rutas
    ));
}

public function reportesRutasWordAction($dia, $ruta,$type){
    $em =$this->getDoctrine()->getManager(); 
    $rutaName = $em->getRepository('AppBundle:Ruta')->find($ruta); 
		//  Comenzamos a armar el documento  
    $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";    
    $date = new \DateTime();
    $output.= "{\\fs28\\qc \" Ruta ". ucfirst(utf8_decode($dia))." ".$rutaName->getName()." \" - ".$date->format('d/m/Y')."\\par}"; 
    $output.= "{\\fs24\\qc ".  utf8_decode('Leyenda asistencia: RV-Recoger viernes / RS-Recoger sábado / RD-Recoger domingo/ CV-Confirmar viernes / CS-Confirmar sábado / CD-Confirmar domingo / LV-Llega viernes / LS-Llega
        sábado / LD-Llega domingo
        ')."\\par}";    
    $output.="{\\fs24\\qc ".  utf8_decode('¡¡¡Atención!!! En esta tabla solo aparecen los repaces que vienen al programa.
     ')."\\par}";
        $output.= "\\par ";  //<-- ENTER
        /* INICIO DE LA TABLA */
        $output.= "{ ";  //<-- Inicio de la tabla
        $output.= "\\trgaph25 "; //<-- márgenes izquierdo y derecho de las celdas=70
        $output.= "\\trleft0 "; // <-- Posición izquierda la primera celda = -10
        /*  Definición de las celdas de datos. Se definen 4 columnas */
        $output.= "
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx550
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs
        \\cellx2000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx5000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx9000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx10500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx11500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx13500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx15000
        ";
        /*Introducción de los títulos en el primer renglón*/
        $output.="{\\fs24\\b\\qc ";              
        $output.= utf8_decode('No')."\\cell "; 
        $output.= utf8_decode('Colegio')."\\cell "; 
        $output.= utf8_decode('Nombre Completo')."\\cell ";
        $output.= utf8_decode('Dirección')."\\cell ";
        $output.= utf8_decode('Telefono')."\\cell ";        
        $output.= utf8_decode('Barrio')."\\cell ";        
        $output.= utf8_decode('N. Padres')."\\cell ";        
        $output.= utf8_decode('Confirmar')."\\cell ";        
        $output.="}";
        $output.= "\\row "; //<-- Fin del renglón de encabezado
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $childs = $em->getRepository('AppBundle:Childs')
        ->buscarRuta($ruta,$dia,$sede,$type);
        $i = 1;
        foreach ($childs as $v){
         $output.= " {\\qc ".$i."}\\cell ".utf8_decode($v->getColegio())."\\cell ".utf8_decode($v->getName())."\\cell ".utf8_decode($v->getAddress())."\\cell ".utf8_decode($v->getPhone())."\\cell ".utf8_decode($v->getBarrio())."\\cell ".utf8_decode($v->getParents())."\\cell ";
         if ($dia == "viernes"){
            $output .= "{\\qc ".$v->getViernes().'V'."}\\cell \n";
        }  
        if ($dia == "sabado"){
            $output .= "{\\qc ".$v->getSabado().'S'."}\\cell \n";
        }
        if ($dia == "domingo"){
            $output .= "{\\qc ".$v->getDomingo().'D'."}\\cell \n";
        }
                 $output.= "\\row "; //<-- Fin del renglón
                 $i++;
             }
        $output.= "} ";  //<-- fin de la tabla
        $output.= "\\par ";  //<-- ENTER

        $output.="}"; //<-- Terminador del RTF
        $response = new Response();
        $response->headers->set('Content-Type', 'application/msword');
        $d = $response->headers->makeDisposition(
        	ResponseHeaderBag::DISPOSITION_INLINE,
            //ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        	'reporte_rutas'.date('d-m-Y').'.rtf',
        	iconv('UTF-8', 'ASCII//TRANSLIT', 'reporte_rutas'.date('d-m-Y').'.rtf')
        );
        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response;
    }

    public function reportesAllRutasWordAction($dia, $ruta,$type){

        //  Comenzamos a armar el documento  
        $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";
        $em =$this->getDoctrine()->getManager(); 
        $rutas = $em->getRepository('AppBundle:Ruta')->findAll(); 
        foreach ($rutas as $route) {
            $date = new \DateTime();
            $output.= "{\\fs28\\qc \" Ruta ". ucfirst(utf8_decode($dia))." ".$route->getName()." \" - ".$date->format('d/m/Y')."\\par}"; 
            $output.= "{\\fs24\\qc ".  utf8_decode('Leyenda asistencia: RV-Recoger viernes / RS-Recoger sábado / RD-Recoger domingo/ CV-Confirmar viernes / CS-Confirmar sábado / CD-Confirmar domingo / LV-Llega viernes / LS-Llega
                sábado / LD-Llega domingo
                ')."\\par}";    
            $output.="{\\fs24\\qc ".  utf8_decode('¡¡¡Atención!!! En esta tabla solo aparecen los repaces que vienen al programa.
             ')."\\par}";
        $output.= "\\par ";  //<-- ENTER
        /* INICIO DE LA TABLA */
        $output.= "{ ";  //<-- Inicio de la tabla
        $output.= "\\trgaph25 "; //<-- márgenes izquierdo y derecho de las celdas=70
        $output.= "\\trleft0 "; // <-- Posición izquierda la primera celda = -10
        /*  Definición de las celdas de datos. Se definen 4 columnas */
        $output.= "
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx550
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs
        \\cellx2000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx5000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx9000
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx10500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx11500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx13500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx15000
        ";
        /*Introducción de los títulos en el primer renglón*/
        $output.="{\\fs24\\b\\qc ";              
        $output.= utf8_decode('No')."\\cell "; 
        $output.= utf8_decode('Colegio')."\\cell "; 
        $output.= utf8_decode('Nombre Completo')."\\cell ";
        $output.= utf8_decode('Dirección')."\\cell ";
        $output.= utf8_decode('Telefono')."\\cell ";        
        $output.= utf8_decode('Barrio')."\\cell ";        
        $output.= utf8_decode('N. Padres')."\\cell ";        
        $output.= utf8_decode('Confirmar')."\\cell ";        
        $output.="}";
        $output.= "\\row "; //<-- Fin del renglón de encabezado
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $childs = $em->getRepository('AppBundle:Childs')
        ->buscarRuta($route,$dia,$sede,$type);
        $i = 1;
        foreach ($childs as $v){
         $output.= " {\\qc ".$i."}\\cell ".utf8_decode($v->getColegio())."\\cell ".utf8_decode($v->getName())."\\cell ".utf8_decode($v->getAddress())."\\cell ".utf8_decode($v->getPhone())."\\cell ".utf8_decode($v->getBarrio())."\\cell ".utf8_decode($v->getParents())."\\cell ";
         if ($dia == "viernes"){
            $output .= "{\\qc ".$v->getViernes().'V'."}\\cell \n";
        }  
        if ($dia == "sabado"){
            $output .= "{\\qc ".$v->getSabado().'S'."}\\cell \n";
        }
        if ($dia == "domingo"){
            $output .= "{\\qc ".$v->getDomingo().'D'."}\\cell \n";
        }
                 $output.= "\\row "; //<-- Fin del renglón
                 $i++;
             }
        $output.= "} ";  //<-- fin de la tabla
        $output.= "\\par\\page\\par ";  //<-- ENTER
    }
        $output.="}"; //<-- Terminador del RTF
        $response = new Response();
        $response->headers->set('Content-Type', 'application/msword');
        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            //ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'reporte_rutas'.date('d-m-Y').'.rtf',
            iconv('UTF-8', 'ASCII//TRANSLIT', 'reporte_rutas'.date('d-m-Y').'.rtf')
        );
        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response;
        
    }

    public function reportesRutasPDFAction($dia, $ruta,$type)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $html2pdf = new Html2Pdf();
        if ($ruta == 'todas') {
            $data = [];
            $rutas = $em->getRepository('AppBundle:Ruta')->findAll();
            foreach ($rutas as $i => $route) {
                $childs = $em->getRepository('AppBundle:Childs')
                ->buscarRuta($route,$dia,$sede,$type);
                $data[]=['ruta'=>$route,'childs'=>$childs];
            }
            $html2pdf->writeHTML($this->renderView('AppBundle:ReporteRutas:todas.html.twig',[
              'data'=> $data,
              'type'=> $type,
              'dia'=> $dia,

          ]));
        }
        if ($ruta != 'todas') {
            $rutaName = $em->getRepository('AppBundle:Ruta')->find($ruta);
            $childs = $em->getRepository('AppBundle:Childs')
            ->buscarRuta($ruta,$dia,$sede,$type);
            $html2pdf->writeHTML($this->renderView('AppBundle:ReporteRutas:una.html.twig',[
                'rutaName'=> $rutaName->getName(),
                'childs'=> $childs,
                'dia'=> $dia,
                'type'=> $type
          ]));
        }
        
        $html2pdf->output('reporteRutas.pdf', 'D');
    }

}
