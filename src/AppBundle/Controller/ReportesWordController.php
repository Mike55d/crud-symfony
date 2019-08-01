<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


/**
     * @Route("reportes")
     */
class ReportesWordController extends Controller
{
    /**
     * @Route("/reporteAsistencia" , name="reportes_asistencia" )
     */
    public function index(Request $request){
        if ($request->get('dia')) {
        return $this->forward('AppBundle:ReportesWord:reporteNinosWord',
            ['dia'=>$request->get('dia'),'type'=>$request->get('type')]);
        }
        return $this->render('AppBundle:reportesWord:index.html.twig');
    }
	
	public function reporteNinosWordAction($dia, $type)
	{
		/*  Comenzamos a armar el documento  */
        $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";
        $date = new \DateTime();
        $output.= "{\\fs28\\qc \" Dia ". ucfirst(utf8_decode($dia))." \" - ".$date->format('d/m/Y')."\\par}";               
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
        $i = 1;
        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>$type,'sede'=>$sede]);  
        foreach ($childs as $v){
           $output.= " {\\qc ".$i."}\\cell ".utf8_decode($v->getColegio())."\\cell ".utf8_decode($v->getName())."\\cell ".utf8_decode($v->getAddress())."\\cell ".utf8_decode($v->getPhone())."\\cell ".utf8_decode($v->getBarrio())."\\cell ".utf8_decode($v->getParents())."\\cell ";
            if ($dia == "viernes"){
                if ($v->getViernes()) {
                    $output .= "{\\qc ".$v->getViernes().' '.$dia."}\\cell \n";
                }else{
                    $output .= "{\\qc  }\\cell \n";
                }
            }  
            if ($dia == "sabado"){
                if ($v->getSabado()) {
                    $output .= "{\\qc ".$v->getSabado().' '.$dia."}\\cell \n";
                }else{
                    $output .= "{\\qc  }\\cell \n";
                }
            }
            if ($dia == "domingo"){
                if ($v->getDomingo()) {
                    $output .= "{\\qc ".$v->getDomingo().' '.$dia."}\\cell \n";
                }else{
                    $output .= "{\\qc  }\\cell \n";
                }
                
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
        	'reportes_registros-'.date('d-m-Y').'.rtf',
        	iconv('UTF-8', 'ASCII//TRANSLIT', 'reporte_registros-'.date('d-m-Y').'.rtf')
        );

        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response; 
    }

	/**
     * @Route("/reportesRutasWord" , name="reportes_rutas_word" )
     */
	public function reportesRutasWordAction($dia = 'sabado' , $ruta = '66 col spring'){
		//  Comenzamos a armar el documento  
       $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";    
       $date = new \DateTime();
       $output.= "{\\fs28\\qc \" Ruta ". ucfirst(utf8_decode($dia))." ".$ruta." \" - ".$date->format('d/m/Y')."\\par}"; 
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
}
