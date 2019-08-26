<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Childs;
use AppBundle\Entity\datesChilds;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
     * @Route("childs")
     */
class ChildsController extends Controller
{
    /**
     * @Route("/primeraVez" , name="childs_first")
     */
    public function indexAction( Request $request)
    {   
        $em =$this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede(); 
        $telefoneros = $em->getRepository('AppBundle:Telefonero')
        ->findBySede($sede); 
        $grupos = $em->getRepository('AppBundle:Grupo')
        ->findBySede($sede);  
        $sedes = $em->getRepository('AppBundle:Sede')->findAll();
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
                'telefoneros'=>$telefoneros,
                'grupos'=>$grupos,
                'sedes'=>$sedes,
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
        $telefoneros = $em->getRepository('AppBundle:Telefonero')
        ->findBySede($sede); 
        $grupos = $em->getRepository('AppBundle:Grupo')
        ->findBySede($sede);  
        $sedes = $em->getRepository('AppBundle:Sede')->findAll();
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['type'=>'discard','sede'=> $sede],['id'=> 'ASC']);  
        return $this->render('AppBundle:Childs:index.html.twig', array(
            'childs' => $childs,
            'lista'=> 'discard',
            'telefoneros'=>$telefoneros,
            'grupos'=>$grupos,
            'sedes'=>$sedes,
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
        $telefoneros = $em->getRepository('AppBundle:Telefonero')
        ->findBySede($sede); 
        $grupos = $em->getRepository('AppBundle:Grupo')
        ->findBySede($sede);  
        $sedes = $em->getRepository('AppBundle:Sede')->findAll();
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
                'telefoneros'=>$telefoneros,
                'grupos'=>$grupos,
                'sedes'=>$sedes,
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
            $child->setRoute($em->getRepository('AppBundle:Ruta')
                ->find($request->get('ruta')));
            $child->setColegio($request->get('colegio'));
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
            if ($request->get('image')) {
             $img = $request->get('image');
             $img = str_replace('data:image/jpeg;base64,', '', $img);
             $img = str_replace(' ', '+', $img);
             $data = base64_decode($img);
             $file = 'image.jpeg';
             $success = file_put_contents($file, $data);
             $file = new File($file);
             $fileName = md5(uniqid()).'.'.$file->guessExtension();
             $file->move($this->getParameter('childs'),$fileName);
             $child->setImage($fileName);
         }
         $child->setType('first');
         $child->setSede($sede);

         $em->persist($child);
         $em->flush();
         return $this->redirectToRoute('childs_first');
     }

     return $this->render('AppBundle:Childs:new.html.twig', array(
        'grupos' => $grupo,
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
    public function editAction(Request $request ,$lista, Childs $child  )
    {
        $em =$this->getDoctrine()->getManager(); 
        $recojerDates = [];
        $recojer = $em->getRepository('AppBundle:datesChilds')
        ->findBy(['child'=>$child->getId(),'event'=>'R']); 
        foreach ($recojer as $recojerData) $recojerDates[] = $recojerData->getDate();

        $confirmarDates = [];
        $confirmar = $em->getRepository('AppBundle:datesChilds')
        ->findBy(['child'=>$child->getId(),'event'=>'C']); 
        foreach ($confirmar as $confirmarData) $confirmarDates[] = $confirmarData->getDate();

        $llegaDates = [];
        $llega = $em->getRepository('AppBundle:datesChilds')
        ->findBy(['child'=>$child->getId(),'event'=>'L']); 
        foreach ($llega as $llegaData) $llegaDates[] = $llegaData->getDate();

        $noVieneDates = [];
        $noviene = $em->getRepository('AppBundle:datesChilds')
        ->findBy(['child'=>$child->getId(),'event'=>'X']); 
        foreach ($noviene as $noVieneData) $noVieneDates[] = $noVieneData->getDate();

        $viernes = new \DateTime('friday this week');
        $formatViernes = $viernes->format('Y-m-d');
        
        $sabado = new \DateTime('saturday this week');
        $formatSabado = $sabado->format('Y-m-d');

        $domingo = new \DateTime('sunday this week');
        $formatDomingo = $domingo->format('Y-m-d');

        $user= $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $sedes = $em->getRepository('AppBundle:Sede')->findAll(); 
        $anterior = 'childs_'.$lista ;
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findAll();
        $route = $em->getRepository('AppBundle:Ruta')->findAll();
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findAll();
        $next = $em->getRepository('AppBundle:Childs')
        ->nextId($child->getId(),$sede,$lista,null);
        $back = $em->getRepository('AppBundle:Childs')
        ->backId($child->getId(),$sede,$lista,null);
        $stars = $em->getRepository('AppBundle:StarsChilds')
        ->findByChild($child->getId()); 
        if ($user->getRola() == 'USER') {
            $telefonero = $user->getTelefonero();
            $next = $em->getRepository('AppBundle:Childs')
            ->nextId($child->getId(),null,$lista,$telefonero);
            $back = $em->getRepository('AppBundle:Childs')
            ->backId($child->getId(),null,$lista,$telefonero);
        }
        
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
            $child->setRoute($em->getRepository('AppBundle:Ruta')
                ->find($request->get('ruta')));
            $child->setColegio($request->get('colegio'));
            $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
                ->find($request->get('telefonero')));
            $child->setObservations($request->get('observations'));
            $child->setComments($request->get('comments'));

            $child->setViernes($request->get('viernes'));
            $child->setSabado($request->get('sabado'));
            $child->setDomingo($request->get('domingo'));
            //Guardar fechas para el calendario

            // buscar viernes
            $idViernes = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatViernes,'child'=>$child->getId()]);
            if ($idViernes)
            {
                $idViernes->setEvent($request->get('viernes'));   
            }else{
                $viernesDate = new datesChilds;
                $viernesDate->setDate($formatViernes);
                $viernesDate->setEvent($request->get('viernes'));
                $viernesDate->setChild($child);
                $em->persist($viernesDate);
            }

            // buscar sabado
            $idSabado = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatSabado,'child'=>$child->getId()]);
            if ($idSabado)
            {
                $idSabado->setEvent($request->get('sabado'));   
            }else{
                $sabadoDate = new datesChilds;
                $sabadoDate->setDate($formatSabado);
                $sabadoDate->setEvent($request->get('sabado'));
                $sabadoDate->setChild($child);
                $em->persist($sabadoDate);
            }

            // buscar domingo
            $idDomingo = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatDomingo,'child'=>$child->getId()]);
            if ($idDomingo)
            {
                $idDomingo->setEvent($request->get('domingo'));   
            }else{
                $domingoDate = new datesChilds;
                $domingoDate->setDate($formatDomingo);
                $domingoDate->setEvent($request->get('domingo'));
                $domingoDate->setChild($child);
                $em->persist($domingoDate);
            }

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
        'routes' => $route,
        'telefoneros' => $telefoneros,
        'next'=>$next,
        'back'=>$back,
        'lista'=> $lista,
        'childNames'=>$childNames,
        'childPhones'=>$childPhones,
        'childEmails'=>$childEmails,
        'childParents'=>$childParents,
        'anterior'=> $anterior,
        'sedes'=>$sedes,
        'stars'=>$stars,
        'recojer'=>$recojerDates,
        'confirmar'=>$confirmarDates,
        'llega'=> $llegaDates,
        'noViene'=>$noVieneDates,
    ));
 }

    /**
     * @Route("/{id}/{type}/del" , name="childs_del")
     */
    public function delAction(Childs $child,$type)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();
        $this->addFlash('notice','Registro removido satisfactoriamente');
        return $this->redirectToRoute('childs_'.$type);
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

        $viernes = new \DateTime('friday this week');
        $formatViernes = $viernes->format('Y-m-d');
        
        $sabado = new \DateTime('saturday this week');
        $formatSabado = $sabado->format('Y-m-d');

        $domingo = new \DateTime('sunday this week');
        $formatDomingo = $domingo->format('Y-m-d');
        
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
        $child->setRoute($em->getRepository('AppBundle:Ruta')
            ->find($request->get('ruta')));
        $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
            ->find($request->get('telefonero')));
        $child->setObservations($request->get('observations'));
        $child->setComments($request->get('comments'));
        $child->setViernes($request->get('viernes'));
        $child->setSabado($request->get('sabado'));
        $child->setDomingo($request->get('domingo'));
        $child->setLat($request->get('lat'));
        $child->setLng($request->get('lng'));

        //Guardar fechas para el calendario

            // buscar viernes
        $idViernes = $em->getRepository('AppBundle:datesChilds')
        ->findOneBy(['date'=>$formatViernes,'child'=>$child->getId()]);
        
        if ($idViernes)
        {
            $idViernes->setEvent($request->get('viernes'));   
        }else{
            $viernesDate = new datesChilds;
            $viernesDate->setDate($formatViernes);
            $viernesDate->setEvent($request->get('viernes'));
            $viernesDate->setChild($child);
            $em->persist($viernesDate);
        }

            // buscar sabado
        $idSabado = $em->getRepository('AppBundle:datesChilds')
        ->findOneBy(['date'=>$formatSabado,'child'=>$child->getId()]);
        if ($idSabado)
        {
            $idSabado->setEvent($request->get('sabado'));   
        }else{
            $sabadoDate = new datesChilds;
            $sabadoDate->setDate($formatSabado);
            $sabadoDate->setEvent($request->get('sabado'));
            $sabadoDate->setChild($child);
            $em->persist($sabadoDate);
        }

            // buscar domingo
        $idDomingo = $em->getRepository('AppBundle:datesChilds')
        ->findOneBy(['date'=>$formatDomingo,'child'=>$child->getId()]);
        if ($idDomingo)
        {
            $idDomingo->setEvent($request->get('domingo'));   
        }else{
            $domingoDate = new datesChilds;
            $domingoDate->setDate($formatDomingo);
            $domingoDate->setEvent($request->get('domingo'));
            $domingoDate->setChild($child);
            $em->persist($domingoDate);
        }
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
    /**
    * @Route("/{list}/{next}/{export}/{id}/export" , name="childs_export")
    */
    public function exportarAction($list,$next,$export,$id){
        $em =$this->getDoctrine()->getManager(); 
        $child = $em->getRepository('AppBundle:Childs')->find($id); 
        $child->setType($export);
        $em->flush();
        $this->addFlash('notice','Exportado satisfactoriamente');
        return $this->redirectToRoute('childs_edit',['lista'=>$list,'id'=>$next]);
    }

 /**
    * @Route("/exportSede" , name="childs_exportSede")
    */
 public function exportarSedeAction(Request $request){
    $em =$this->getDoctrine()->getManager(); 
    $sede = $em->getRepository('AppBundle:Sede')->find($request->get('sede')); 
    $lista = $request->get('lista');
    $next = $request->get('next');
    $child = $em->getRepository('AppBundle:Childs')
    ->find($request->get('id')); 
    $child->setSede($sede);
    $em->flush();
    $this->addFlash('notice','Exportado satisfactoriamente');
    return $this->redirectToRoute('childs_edit',['lista'=>$lista,'id'=>$next]);
}


}
