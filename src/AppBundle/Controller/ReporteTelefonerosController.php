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
     * @Route("reporteTelefoneros" )
     */
class ReporteTelefonerosController extends Controller
{
    /**
     * @Route("/" , name="registros_telefoneros")
     */
    public function indexAction(Request $request )
    {	
      $em =$this->getDoctrine()->getManager(); 
      $user = $this->get('security.token_storage')
      ->getToken()->getUser();
      $sede = $user->getSede();
      $telefoneros=$em->getRepository('AppBundle:Telefonero')->findBySede($sede); 
      if ($request->get('formato') == 'rtf') {
          if ($request->get('telefonero') && $request->get('telefonero') != 'todos') {
            return $this->forward('AppBundle:ReporteTelefoneros:reportesTelefonerosWord',
                ['telefonero'=>$request->get('telefonero'),
                'type'=>$request->get('type')]);
        }
        if ($request->get('telefonero') && $request->get('telefonero') == 'todos') {
            return $this->forward('AppBundle:ReporteTelefoneros:reportesAllTelefonerosWord',
                ['telefonero'=>$request->get('telefonero'),
                'type'=>$request->get('type')]);
        }
    }
    if ($request->get('formato') == 'pdf') {
        return $this->forward('AppBundle:ReporteTelefoneros:reportesTelefonerosPDF',
            ['telefonero'=>$request->get('telefonero'),
              'type'=>$request->get('type')]);
    }
    return $this->render('AppBundle:ReporteTelefoneros:index.html.twig', array(
        'telefoneros'=> $telefoneros
    ));
}

public function reportesTelefonerosWordAction($telefonero,$type){
    $em =$this->getDoctrine()->getManager(); 
    $telefoneroName= $em->getRepository('AppBundle:Telefonero')->find($telefonero); 
		/*  Comenzamos a armar el documento  */
        $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";  
        $output.= "\\par ";               
        $output.= "{\\fs28\\qc\\b ".  utf8_decode('Listado de Niños')." \\par}";
        $output.= "\\par ";               
        $output.= "{ ";  //<-- Inicio de la tabla

        $output.= "\\trgaph50 "; //<-- márgenes izquierdo y derecho de las celdas=70
        $output.= "\\trleft-10 "; // <-- Posición izquierda la primera celda = -10

        /*  Comenzamos a armar el documento  */
        $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";
        $output.= "\\par ";               
        
        $output.= "{\\fs28\\qc\\b ".  utf8_decode('Listado de Niños  '.$telefoneroName->getName())." \\par}";
        $output.= "\\par ";               
        /* INICIO DE LA TABLA */
        $output.= "{ ";  //<-- Inicio de la tabla

        $output.= "\\trgaph50 "; //<-- márgenes izquierdo y derecho de las celdas=70
        $output.= "\\trleft-10 "; // <-- Posición izquierda la primera celda = -10

       /*  Definición de las celdas de datos. Se definen 4 columnas */
        $output.= "
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx1550
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs
        \\cellx2500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx3500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx4500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx5500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx6500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx7500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx8500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx9500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx11500
        ";
        /*Introducción de los títulos en el primer renglón*/
        $output.="{\\fs18\\b\\qc ";              
        $output.= utf8_decode('Observaciones')."\\cell "; 
        $output.= utf8_decode('Colegio')."\\cell "; 
        $output.= utf8_decode('Grupo')."\\cell "; 
        $output.= utf8_decode('Nombre Completo')."\\cell ";
        $output.= utf8_decode('Telefono')."\\cell ";        
        $output.= utf8_decode('Dirección')."\\cell ";
        $output.= utf8_decode('Barrio')."\\cell ";        
        $output.= utf8_decode('Ruta')."\\cell ";        
        $output.= utf8_decode('N. Padres')."\\cell ";        
        $output.= utf8_decode('Comentarios')."\\cell ";        
        $output.="}";
        $output.= "\\row "; //<-- Fin del renglón de encabezado
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();	
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>$type,'sede'=> $sede,'telefonero'=>$telefonero]);
        $i = 1;
        foreach ($childs as $v){
        $output.= " {\\fs18 ".utf8_decode($v->getObservations())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getColegio())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getGrupo())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getName())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getPhone())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getAddress())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getBarrio())."}\\cell ".
                    "{\\fs18\\qc ".utf8_decode($v->getRoute())."}\\cell  ".
                    "{\\fs18\\qc ".utf8_decode($v->getParents())."}\\cell  ".
                    "{\\fs18 ".utf8_decode($v->getComments())."}\\cell ";
                           
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
        	'registroTelefonero'.date('d-m-Y').'.rtf',
        	iconv('UTF-8', 'ASCII//TRANSLIT', 'registroTelefonero'.date('d-m-Y').'.rtf')
        );
        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response;
    }

    public function reportesAllTelefonerosWordAction($telefonero,$type){

        //  Comenzamos a armar el documento  
        $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";
        $em =$this->getDoctrine()->getManager(); 
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findAll(); 
        foreach ($telefoneros as $telefonero) {
           
        $output.= "{\\fs28\\qc\\b ".  utf8_decode('Listado de Niños  '.$telefonero->getName())." \\par}";
        $output.= "\\par ";               
        /* INICIO DE LA TABLA */
        $output.= "{ ";  //<-- Inicio de la tabla

        $output.= "\\trgaph50 "; //<-- márgenes izquierdo y derecho de las celdas=70
        $output.= "\\trleft-10 "; // <-- Posición izquierda la primera celda = -10

       /*  Definición de las celdas de datos. Se definen 4 columnas */
        $output.= "
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx1550
        \\clbrdrl\\brdrw10\\brdrs 
        \\clbrdrt\\brdrw10\\brdrs 
        \\clbrdrr\\brdrw10\\brdrs 
        \\clbrdrb\\brdrw10\\brdrs
        \\cellx2500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx3500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx4500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx5500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx6500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx7500    
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx8500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx9500
        \\clbrdrl\\brdrw10\\brdrs
        \\clbrdrt\\brdrw10\\brdrs
        \\clbrdrr\\brdrw10\\brdrs
        \\clbrdrb\\brdrw10\\brdrs 
        \\cellx11500
        ";
        /*Introducción de los títulos en el primer renglón*/
        $output.="{\\fs18\\b\\qc ";              
        $output.= utf8_decode('Observaciones')."\\cell "; 
        $output.= utf8_decode('Colegio')."\\cell "; 
        $output.= utf8_decode('Grupo')."\\cell "; 
        $output.= utf8_decode('Nombre Completo')."\\cell ";
        $output.= utf8_decode('Telefono')."\\cell ";        
        $output.= utf8_decode('Dirección')."\\cell ";
        $output.= utf8_decode('Barrio')."\\cell ";        
        $output.= utf8_decode('Ruta')."\\cell ";        
        $output.= utf8_decode('N. Padres')."\\cell ";        
        $output.= utf8_decode('Comentarios')."\\cell ";        
        $output.="}";
        $output.= "\\row "; //<-- Fin del renglón de encabezado
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();	
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>$type,'sede'=> $sede,'telefonero'=>$telefonero]);
        $i = 1;
        foreach ($childs as $v){
        $output.= " {\\fs18 ".utf8_decode($v->getObservations())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getColegio())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getGrupo())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getName())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getPhone())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getAddress())."}\\cell ".
                    "{\\fs18 ".utf8_decode($v->getBarrio())."}\\cell ".
                    "{\\fs18\\qc ".utf8_decode($v->getRoute())."}\\cell  ".
                    "{\\fs18\\qc ".utf8_decode($v->getParents())."}\\cell  ".
                    "{\\fs18 ".utf8_decode($v->getComments())."}\\cell ";
                           
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
            'registroTelefonero'.date('d-m-Y').'.rtf',
            iconv('UTF-8', 'ASCII//TRANSLIT', 'registroTelefonero'.date('d-m-Y').'.rtf')
        );
        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response;
        
    }

    public function reportesTelefonerosPDFAction($telefonero,$type)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $html2pdf = new Html2Pdf();
        if ($telefonero == 'todos') {
            $data = [];
            $telefoneros = $em->getRepository('AppBundle:Telefonero')->findAll();
            foreach ($telefoneros as $i => $telefon) {
                $childs = $em->getRepository('AppBundle:Childs')
        				->findBy(['type'=>$type,'sede'=> $sede,'telefonero'=>$telefon]);
                $data[]=['telefonero'=>$telefon,'childs'=>$childs];
            }
            $html2pdf->writeHTML($this->renderView('AppBundle:ReporteTelefoneros:todas.html.twig',[
              'data'=> $data,
              'type'=> $type,

          ]));
        }
        if ($telefonero != 'todos') {
            $telefoneroName = $em->getRepository('AppBundle:Telefonero')->find($telefonero);
            $childs = $em->getRepository('AppBundle:Childs')
        				->findBy(['type'=>$type,'sede'=> $sede,'telefonero'=>$telefonero]);
            $html2pdf->writeHTML($this->renderView('AppBundle:ReporteTelefoneros:una.html.twig',[
                'telefonero'=> $telefoneroName->getName(),
                'childs'=> $childs,
                'type'=> $type
          ]));
        }
        
        $html2pdf->output('reporteRutas.pdf', 'D');
    }

    /**
     * @Route("/{type}/printUserWord" , name="printUserWord")
     */
    public function printUser($type){
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $telefonero = $user->getTelefonero()->getId();
        return $this->forward('AppBundle:ReporteTelefoneros:reportesTelefonerosWord',
                ['telefonero'=>$telefonero,
                'type'=>$type]);
    }

    /**
     * @Route("/{type}/printUserPdf" , name="printUserPdf")
     */
    public function printUserPdf($type){
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $telefonero = $user->getTelefonero()->getId();
         return $this->forward('AppBundle:ReporteTelefoneros:reportesTelefonerosPDF',
            ['telefonero'=>$telefonero,
              'type'=>$type]);
    }

    }
