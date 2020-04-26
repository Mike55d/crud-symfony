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
     * @Route("/{telf}/{miGrupo}/first" , name="childs_first")
     */
    public function indexAction( Request $request ,$telf,$miGrupo)
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
        $filtroName = '';
        if ($user->getRola() == 'USER') {
            $telf = $user->getTelefonero()->getId();
        }
        if ($telf) {
            $filtroName = $em->getRepository('AppBundle:Telefonero')->find($telf);
            $query = [
                'type'=>'first',
                'telefonero'=>$telf,
                'sede'=> $sede
            ];
        }else{
            if ($miGrupo) {
                $filtroName = $em->getRepository('AppBundle:Grupo')->find($miGrupo);
                $query = [
                    'type'=>'first',
                    'grupo'=>$miGrupo,
                    'sede'=> $sede
                ];
            }else{
                $query = [
                    'type'=>'first',
                    'sede'=> $sede
                ];
            }
        }
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
                'telf'=>$telf,
                'miGrupo'=>$miGrupo,
                'filtroName'=>$filtroName,
            ));
        }

    /**
     * @Route("/{telf}/{miGrupo}/descartados" , name="childs_discard")
     */
    public function discardAction( Request $request ,$telf,$miGrupo)
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
            'telf'=>$telf,
            'miGrupo'=>$miGrupo,
        ));
    }

    /**
     * @Route("/{telf}/{miGrupo}/frequent" , name="childs_frequent")
     */
    public function frequentAction( Request $request ,$telf,$miGrupo)
    {   
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        $telefoneros = $em->getRepository('AppBundle:Telefonero')
        ->findBySede($sede); 
        $grupos = $em->getRepository('AppBundle:Grupo')
        ->findBySede($sede); 
        $filtroName = '';
        $sedes = $em->getRepository('AppBundle:Sede')->findAll();
        if ($user->getRola() == 'USER') {
            $telf = $user->getTelefonero()->getId();
        }
        if ($telf) {
            $filtroName = $em->getRepository('AppBundle:Telefonero')->find($telf);
            $query = [
                'type'=>'frequent',
                'telefonero'=>$telf,
                'sede'=> $sede
            ];
        }else{
            if ($miGrupo) {
                $filtroName = $em->getRepository('AppBundle:Grupo')->find($miGrupo);
                $query = [
                    'type'=>'frequent',
                    'grupo'=>$miGrupo,
                    'sede'=> $sede
                ];
            }else{
                $query = [
                    'type'=>'frequent',
                    'sede'=> $sede
                ];
            }
        }
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy($query,['id'=> 'ASC']);   
        return $this->render('AppBundle:Childs:index.html.twig', array(
            'childs' => $childs,
            'lista'=> 'frequent',
            'telefoneros'=>$telefoneros,
            'grupos'=>$grupos,
            'sedes'=>$sedes,
            'telf'=>$telf,
            'miGrupo'=>$miGrupo,
            'filtroName'=>$filtroName,
        ));
    }

    /**
     * @Route("/new" , name="childs_new")
     */
    public function newAction(Request $request)
    {
        $childNames =[];
        $childPhones =[];
        $childEmails =[];
        $childParents =[];
        $childBarrio =[];
        $childColegio =[];
        $em =$this->getDoctrine()->getManager(); 
        $user = $this->get('security.token_storage')
        ->getToken()->getUser();
        $sede = $user->getSede();
        //obtener selects
        $grupo = $em->getRepository('AppBundle:Grupo')->findBy(['sede'=>$sede]);
        $route = $em->getRepository('AppBundle:Ruta')->findBy(['sede'=>$sede]);
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findBy(['sede'=>$sede]);
        
        $viernes = new \DateTime('friday this week');
        $formatViernes = $viernes->format('Y-m-d');
        
        $sabado = new \DateTime('saturday this week');
        $formatSabado = $sabado->format('Y-m-d');

        $domingo = new \DateTime('sunday this week');
        $formatDomingo = $domingo->format('Y-m-d');
        // datos autocompletar 
        $childs = $em->getRepository('AppBundle:Childs')
        ->findBy(['sede'=> $sede]);
        foreach ($childs as $child){
            if ($child->getName() && !in_array($child->getName(),$childNames)) {
                $childNames[]= $child->getName();
            }
        }
        foreach ($childs as $child) {
            if ($child->getPhone() && !in_array($child->getPhone(),$childPhones)) {
                $childPhones[]= $child->getPhone();
            }
        }
        foreach ($childs as $child) {
            if ($child->getEmail() && !in_array($child->getEmail(),$childEmails)) {
                $childEmails[]= $child->getEmail();
            }
        }
        foreach ($childs as $child) {
            if ( $child->getParents() && !in_array($child->getParents(),$childParents)) {
                $childParents[]= $child->getParents();
            }
        }
        foreach ($childs as $child) {
            if ( $child->getBarrio() && !in_array($child->getBarrio(),$childBarrio)) {
                $childBarrio[]= $child->getBarrio();
            }
        }
        foreach ($childs as $child) {
            if ( $child->getColegio() && !in_array($child->getColegio(),$childColegio)) {
                $childColegio[]= $child->getColegio();
            }
        }
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
            if ($user->getRola() == 'USER') {
                $child->setTelefonero($user->getTelefonero());
            }else{
                $child->setTelefonero($em->getRepository('AppBundle:Telefonero')
                    ->find($request->get('telefonero')));
            }
            $child->setObservations($request->get('observations') ?? 'ninguna');
            $child->setComments($request->get('comments') ?? 'ninguno');
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
                if ($request->get('viernes')) {
                    $viernesDate = new datesChilds;
                    $viernesDate->setDate($formatViernes);
                    $viernesDate->setEvent($request->get('viernes'));
                    $viernesDate->setChild($child);
                    $em->persist($viernesDate);
                }
            }

            // buscar sabado
            $idSabado = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatSabado,'child'=>$child->getId()]);
            if ($idSabado)
            {
                $idSabado->setEvent($request->get('sabado'));   
            }else{
                if ($request->get('sabado')) {
                    $sabadoDate = new datesChilds;
                    $sabadoDate->setDate($formatSabado);
                    $sabadoDate->setEvent($request->get('sabado'));
                    $sabadoDate->setChild($child);
                    $em->persist($sabadoDate);
                }
            }

            // buscar domingo
            $idDomingo = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatDomingo,'child'=>$child->getId()]);
            if ($idDomingo)
            {
                $idDomingo->setEvent($request->get('domingo'));   
            }else{
                if ($request->get('domingo')) {
                    $domingoDate = new datesChilds;
                    $domingoDate->setDate($formatDomingo);
                    $domingoDate->setEvent($request->get('domingo'));
                    $domingoDate->setChild($child);
                    $em->persist($domingoDate);
                }
                
            }
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
           return $this->redirectToRoute('childs_first',
            [
            'telf'=>0,
            'miGrupo'=>0]);
       }

       return $this->render('AppBundle:Childs:new.html.twig', array(
        'grupos' => $grupo,
        'routes' => $route,
        'telefoneros' => $telefoneros,
        'childNames'=>$childNames,
        'childPhones'=>$childPhones,
        'childEmails'=>$childEmails,
        'childParents'=>$childParents,
        'childColegio'=>$childColegio,
        'childBarrio'=>$childBarrio,
    ));
   }

    /**
     * @Route("/{lista}/{id}/{telf}/{miGrupo}/edit" , name="childs_edit")
     */
    public function editAction(Request $request ,$lista, Childs $child ,$telf,$miGrupo )
    {
        $childNames =[];
        $childPhones =[];
        $childEmails =[];
        $childParents =[];
        $childBarrio =[];
        $childColegio =[];
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
        $grupo = $em->getRepository('AppBundle:Grupo')->findBy(['sede'=>$sede]);
        $route = $em->getRepository('AppBundle:Ruta')->findBy(['sede'=>$sede]);
        $telefoneros = $em->getRepository('AppBundle:Telefonero')->findBy(['sede'=>$sede]);
        $next = $em->getRepository('AppBundle:Childs')
        ->nextId($child->getId(),$sede,$lista,$telf,$miGrupo);
        $back = $em->getRepository('AppBundle:Childs')
        ->backId($child->getId(),$sede,$lista,$telf,$miGrupo);
        $stars = $em->getRepository('AppBundle:StarsChilds')
        ->findByChild($child->getId()); 
        if ($user->getRola() == 'USER') {
            $telefonero = $user->getTelefonero();
            $next = $em->getRepository('AppBundle:Childs')
            ->nextId($child->getId(),null,$lista,$telefonero,null);
            $back = $em->getRepository('AppBundle:Childs')
            ->backId($child->getId(),null,$lista,$telefonero,null);
        }
        
         // datos autocompletar 
        $kids = $em->getRepository('AppBundle:Childs')
        ->findBy(['sede'=> $sede]);
        foreach ($kids as $kid){
            if ($kid->getName() && !in_array($kid->getName(),$childNames)) {
                $childNames[]= $kid->getName();
            }
        }
        foreach ($kids as $kid) {
            if ($kid->getPhone() && !in_array($kid->getPhone(),$childPhones)) {
                $childPhones[]= $kid->getPhone();
            }
        }
        foreach ($kids as $kid) {
            if ($kid->getEmail() && !in_array($kid->getEmail(),$childEmails)) {
                $childEmails[]= $kid->getEmail();
            }
        }
        foreach ($kids as $kid) {
            if ( $kid->getParents() && !in_array($kid->getParents(),$childParents)) {
                $childParents[]= $kid->getParents();
            }
        }
        foreach ($kids as $kid) {
            if ( $kid->getBarrio() && !in_array($kid->getBarrio(),$childBarrio)) {
                $childBarrio[]= $kid->getBarrio();
            }
        }
        foreach ($kids as $kid) {
            if ( $kid->getColegio() && !in_array($kid->getColegio(),$childColegio)) {
                $childColegio[]= $kid->getColegio();
            }
        }

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
            $child->setExtra($request->get('extra'));
            //Guardar fechas para el calendario

            // buscar viernes
            $idViernes = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatViernes,'child'=>$child->getId()]);
            if ($idViernes)
            {
                $idViernes->setEvent($request->get('viernes'));   
            }else{
                if ($request->get('viernes')) {
                    $viernesDate = new datesChilds;
                    $viernesDate->setDate($formatViernes);
                    $viernesDate->setEvent($request->get('viernes'));
                    $viernesDate->setChild($child);
                    $em->persist($viernesDate);
                }
            }

            // buscar sabado
            $idSabado = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatSabado,'child'=>$child->getId()]);
            if ($idSabado)
            {
                $idSabado->setEvent($request->get('sabado'));   
            }else{
                if ($request->get('sabado')) {
                    $sabadoDate = new datesChilds;
                    $sabadoDate->setDate($formatSabado);
                    $sabadoDate->setEvent($request->get('sabado'));
                    $sabadoDate->setChild($child);
                    $em->persist($sabadoDate);
                }
            }

            // buscar domingo
            $idDomingo = $em->getRepository('AppBundle:datesChilds')
            ->findOneBy(['date'=>$formatDomingo,'child'=>$child->getId()]);
            if ($idDomingo)
            {
                $idDomingo->setEvent($request->get('domingo'));   
            }else{
                if ($request->get('domingo')) {
                    $domingoDate = new datesChilds;
                    $domingoDate->setDate($formatDomingo);
                    $domingoDate->setEvent($request->get('domingo'));
                    $domingoDate->setChild($child);
                    $em->persist($domingoDate);
                }
                
            }

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
           $em->persist($child);
           $em->flush();
           return $this->redirectToRoute('childs_edit',
            ['lista'=>$lista, 
            'id'=> $child->getId(),
            'telf'=>$telf,
            'miGrupo'=>$miGrupo]);
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
        'childColegio'=>$childColegio,
        'childBarrio'=>$childBarrio,
        'anterior'=> $anterior,
        'sedes'=>$sedes,
        'stars'=>$stars,
        'recojer'=>$recojerDates,
        'confirmar'=>$confirmarDates,
        'llega'=> $llegaDates,
        'noViene'=>$noVieneDates,
        'telf'=>$telf,
        'miGrupo'=>$miGrupo,
    ));
   }

    /**
     * @Route("/{telf}/{miGrupo}/{id}/{type}/del" , name="childs_del")
     */
    public function delAction($telf,$miGrupo,Childs $child,$type)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();
        $this->addFlash('notice','Registro removido satisfactoriamente');
        return $this->redirectToRoute('childs_'.$type,
            [ 
            'telf'=>$telf,
            'miGrupo'=>$miGrupo]);
    }

    /**
     * @Route("/{list}/{id}/{telf}/{miGrupo}/{next}/del_edit" , name="childs_del_edit")
     */
    public function delEditAction($list,Childs $child,$telf,$miGrupo,$next)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($child);
        $em->flush();
        $this->addFlash('notice','Registro removido satisfactoriamente');
        return $this->redirectToRoute('childs_edit',
            ['lista'=>$list, 
            'id'=> $next,
            'telf'=>$telf,
            'miGrupo'=>$miGrupo]);
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
        $child->setExtra(null);
    }
    foreach ($childsFrequent as $child){
        $child->setViernes(null);
        $child->setSabado(null);
        $child->setDomingo(null);
        $child->setExtra(null);
    }
    $this->addFlash('notice','Se han limpiado los registros');
    $em->flush();
    return $this->redirectToRoute('homepage');
}

 /**
     * @Route("/cleanCalendar" , name="childs_cleanCalendar")
     */
 public function cleanCalendarAction()
 {
    $em = $this->getDoctrine()->getManager();
    $user = $this->get('security.token_storage')
    ->getToken()->getUser();
    $sede = $user->getSede()->getId(); 
    $fechas = $em->getRepository('AppBundle:datesChilds')->findAll();
    foreach ($fechas as $fecha) {
        if ($fecha->getChild()->getSede()->getId() == $sede) {
         $em->remove($fecha);
         $em->flush();
     }
 } 
 $this->addFlash('notice','Se han limpiado los Calendarios');
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
            if ($request->get('viernes')) {
                $viernesDate = new datesChilds;
                $viernesDate->setDate($formatViernes);
                $viernesDate->setEvent($request->get('viernes'));
                $viernesDate->setChild($child);
                $em->persist($viernesDate);
            }
        }

            // buscar sabado
        $idSabado = $em->getRepository('AppBundle:datesChilds')
        ->findOneBy(['date'=>$formatSabado,'child'=>$child->getId()]);
        if ($idSabado)
        {
            $idSabado->setEvent($request->get('sabado'));   
        }else{
            if ($request->get('sabado')) {
                $sabadoDate = new datesChilds;
                $sabadoDate->setDate($formatSabado);
                $sabadoDate->setEvent($request->get('sabado'));
                $sabadoDate->setChild($child);
                $em->persist($sabadoDate);
            }
        }

            // buscar domingo
        $idDomingo = $em->getRepository('AppBundle:datesChilds')
        ->findOneBy(['date'=>$formatDomingo,'child'=>$child->getId()]);
        if ($idDomingo)
        {
            $idDomingo->setEvent($request->get('domingo'));   
        }else{
            if ($request->get('domingo')) {
                $domingoDate = new datesChilds;
                $domingoDate->setDate($formatDomingo);
                $domingoDate->setEvent($request->get('domingo'));
                $domingoDate->setChild($child);
                $em->persist($domingoDate);
            }
            
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
    * @Route("/{list}/{next}/{export}/{id}/{telf}/{miGrupo}/export" , name="childs_export")
    */
    public function exportarAction($list,$next,$export,$id,$telf,$miGrupo){
        $em =$this->getDoctrine()->getManager(); 
        $child = $em->getRepository('AppBundle:Childs')->find($id); 
        $child->setType($export);
        $em->flush();
        $this->addFlash('notice','Exportado satisfactoriamente');
        return $this->redirectToRoute('childs_edit',
            ['lista'=>$list, 
            'id'=> $next,
            'telf'=>$telf,
            'miGrupo'=>$miGrupo]);
    }

 /**
    * @Route("/{telf}/{miGrupo}/exportSede" , name="childs_exportSede")
    */
 public function exportarSedeAction(Request $request ,$telf,$miGrupo){
    $em =$this->getDoctrine()->getManager(); 
    $sede = $em->getRepository('AppBundle:Sede')->find($request->get('sede')); 
    $lista = $request->get('lista');
    $next = $request->get('next');
    $child = $em->getRepository('AppBundle:Childs')
    ->find($request->get('id')); 
    $child->setSede($sede);
    $em->flush();
    $this->addFlash('notice','Exportado satisfactoriamente');
    return $this->redirectToRoute('childs_edit',
            ['lista'=>$lista, 
            'id'=> $next,
            'telf'=>$telf,
            'miGrupo'=>$miGrupo]);
}


}
