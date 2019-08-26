<?php

namespace AppBundle\Repository;

/**
 * ChildsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChildsRepository extends \Doctrine\ORM\EntityRepository
{
	public function nextId($id,$sede,$lista,$telefonero){

		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		   ->from('AppBundle:Childs', 'c')
		   ->where('c.type = :first AND c.id > :id ')
		   //->andWhere('c.id > :id')
		   ->setParameter('id', $id)
		   ->setParameter('first', $lista)
		   ->setMaxResults(1)
		   ->orderBy('c.id', 'ASC');
		   if ($telefonero) {
		   	$qb->andWhere('c.telefonero = :telefonero')
		   	->setParameter('telefonero',$telefonero);
		   }
		   if ($sede) {
		   	$qb->andWhere('c.sede = :sede')
		   	->setParameter('sede',$sede);
		   }
	  $query = $qb->getQuery();
	  return $query->getResult();
	}

	public function backId($id,$sede,$lista,$telefonero){

		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		   ->from('AppBundle:Childs', 'c')
		   ->where('c.type = :first AND c.id < :id ')
		   //->andWhere('c.id > :id')
		   ->setParameter('id', $id)
		   ->setParameter('first', $lista)
		   ->setMaxResults(1)
		   ->orderBy('c.id', 'DESC');
		   if ($telefonero) {
		   	$qb->andWhere('c.telefonero = :telefonero')
		   	->setParameter('telefonero',$telefonero);
		   }
		   if ($sede) {
		   	$qb->andWhere('c.sede = :sede')
		   	->setParameter('sede',$sede);
		   }
	  $query = $qb->getQuery();
	  return $query->getResult();
	}

	public function buscarRuta($ruta,$dia,$sede,$type){

		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		   ->from('AppBundle:Childs', 'c')
		   ->where('c.type = :type AND c.sede = :sede')
		   //->andWhere('c.id > :id')
		   ->setParameter('sede', $sede)
		   ->setParameter('type', $type)
		   ->orderBy('c.id', 'ASC');
		    if ($ruta != 'todas') {
		   	$qb->andWhere('c.route = :ruta')
		   ->setParameter('ruta', $ruta);
		   }
		   if ($dia == 'viernes') {
		   	$qb->andWhere('c.viernes IS NOT NULL AND c.viernes != :x AND c.viernes != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
		   if ($dia == 'sabado') {
		   	$qb->andWhere('c.sabado IS NOT NULL AND c.sabado != :x AND c.sabado != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
		   if ($dia == 'domingo') {
		   	$qb->andWhere('c.domingo IS NOT NULL AND c.domingo != :x AND c.domingo != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
	  $query = $qb->getQuery();
	  return $query->getResult();
	}

	public function buscarAsist($dia,$sede,$type){

		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		   ->from('AppBundle:Childs', 'c')
		   ->where('c.type = :type AND c.sede = :sede')
		   //->andWhere('c.id > :id')
		   ->setParameter('sede', $sede)
		   ->setParameter('type', $type)
		   ->orderBy('c.id', 'ASC');
		   if ($dia == 'viernes') {
		   	$qb->andWhere('c.viernes IS NOT NULL AND c.viernes != :x AND c.viernes != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
		   if ($dia == 'sabado') {
		   	$qb->andWhere('c.sabado IS NOT NULL AND c.sabado != :x AND c.sabado != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
		   if ($dia == 'domingo') {
		   	$qb->andWhere('c.domingo IS NOT NULL AND c.domingo != :x AND c.domingo != :l')
		   ->setParameter('x', 'X')
		   ->setParameter('l', 'L');
		   }
	  $query = $qb->getQuery();
	  return $query->getResult();
	}

	public function listaHome($telefonero,$sede){
		$em = $this->getEntityManager(); 
		$qb = $em->createQueryBuilder();
		$qb->select('c')
		   ->from('AppBundle:Childs', 'c')
		   ->where('c.type = :first OR c.type = :frequent AND c.sede = :sede')
		   //->andWhere('c.id > :id')
		   ->setParameter('sede', $sede)
		   ->setParameter('first', 'first')
		   ->setParameter('frequent', 'frequent')
		   ->orderBy('c.id', 'ASC');
		   if ($telefonero) {
		   	$qb->andWhere('c.telefonero = :telefonero')
		   ->setParameter('telefonero', '$telefonero');
		   }
	  $query = $qb->getQuery();
	  return $query->getResult();
	}
}
