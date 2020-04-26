<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Spipu\Html2Pdf\Html2Pdf;

class UpdateMultipleController extends Controller
{
    /**
     * @Route("/{telf}/{miGrupo}/updateMUltiple", name="update_multiple")
     */
    public function updateMultipleAction($telf,$miGrupo,Request $request)
    {
    	$em =$this->getDoctrine()->getManager(); 
    	$type = $request->get('type');
    	$childs = $request->get('childs');
    	$editar = $request->get('editar');
    	$idEditar = $request->get('idEditar');
    	if ($editar == 'telefonero') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$telefonero = $em->getRepository('AppBundle:Telefonero')
    			->find($idEditar); 
    			$obChild->setTelefonero($telefonero);
    		}
    	}
    	if ($editar == 'sede') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$sede = $em->getRepository('AppBundle:Sede')
    			->find($idEditar); 
    			$obChild->setSede($sede);
    		}
    	}
    	if ($editar == 'grupo') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$grupo = $em->getRepository('AppBundle:Grupo')
    			->find($idEditar); 
    			$obChild->setGrupo($grupo);
    		}
    	}
    	if ($editar == 'tipo') {
    		foreach ($childs as $child) {
    			$obChild = $em->getRepository('AppBundle:Childs')
    			->find($child); 
    			$obChild->setType($idEditar);
    		}
    	}
        if ($editar == 'imprimir') {
            if ($request->get('idEditar') == 'rtf') {
                return $this->forward('AppBundle:UpdateMultiple:reporteNinosWord',
                    ['childs'=>$request->get('childs')]);
            }
            if ($request->get('idEditar') == 'pdf') {
                return $this->forward('AppBundle:UpdateMultiple:reporteNinosPdf',
                    ['childs'=>$request->get('childs'),
                    'foto'=>$request->get('foto')]);
            }
        }
        $em->flush();
        return $this->redirectToRoute('childs_'.$type,['telf'=>$telf,
            'miGrupo'=>$miGrupo]);
    }

    public function reporteNinosPdfAction($childs,$foto){

        $em = $this->getDoctrine()->getManager();
        $html2pdf = new Html2Pdf('L');
        foreach ($childs as $kid) {
            $objKid = $em->getRepository('AppBundle:Childs')->find($kid);
            $kids[] =  $objKid;
        }
        $html2pdf->writeHTML($this->renderView('AppBundle:UpdateMultiple:pdf.html.twig',[
            'childs'=> $kids,
            'foto'=>$foto
        ]));
        $html2pdf->output('reporteAsistencia.pdf', 'D');

    }

    public function reporteNinosWordAction($childs)
    {
      /*  Comenzamos a armar el documento  */
      $output="{\\rtf1\\anci\\deff0\\paperw15842\\paperh12242\\margl250\\margr250";
      $date = new \DateTime();
      $output.= "{\\fs28\\qc \"".'Listado de Registros'." \" - ".$date->format('d/m/Y')."\\par}";               
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
        $output.= utf8_decode('Asistencia')."\\cell ";        
        $output.="}";
        $output.= "\\row "; //<-- Fin del renglón de encabezado
        $i = 1;
        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        foreach ($childs as $kid) {
            $objKid = $em->getRepository('AppBundle:Childs')->find($kid);
            $kids[] =  $objKid;
        } 
        foreach ($kids as $v){
           $output.= " {\\qc ".$i."}\\cell ".utf8_decode($v->getColegio())."\\cell ".utf8_decode($v->getName())."\\cell ".utf8_decode($v->getAddress())."\\cell ".utf8_decode($v->getPhone())."\\cell ".utf8_decode($v->getBarrio())."\\cell ".utf8_decode($v->getParents())."\\cell "."\\cell ";
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
            'listadoDeNiños-'.date('d-m-Y').'.rtf',
            iconv('UTF-8', 'ASCII//TRANSLIT', 'listadoDeNiños-'.date('d-m-Y').'.rtf')
        );
        $response->headers->set('Content-Disposition', $d);
        $response->setContent($output);
        return $response; 
    }

}
