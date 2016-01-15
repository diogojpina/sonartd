<?php

namespace Sonar\Model;

use Sonar\Entity\User;
use Sonar\Entity\Project;

class UserModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;	
		$this->repository = $sm->getRepository('Sonar\Entity\User');
	}
	
	public function findAll() {
		return $this->repository->findAll();
	}
	
	public function findByProject(Project $project) {
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('u')
		->from('Sonar\Entity\User', 'u')
		->leftJoin('u.groups', 'g')
		->leftJoin('g.roles', 'gr')
		->leftJoin('gr.project', 'p1')
		
		->leftJoin('u.roles', 'r')
		->leftJoin('r.project', 'p2')
		
		->where('(p1 = ?1 or p2 = ?1) and gr.role = ?2')
		->orderBy('u.name', 'ASC')
		->groupBy('u.id')
		->setParameter(1, $project)
		->setParameter(2, 'user');
		
		$query = $qb->getQuery();
		$results = $query->getResult();

		return $results;
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\User', $id);
	}
	
	public function getByLogin($login) {
		return $this->repository->findOneBy(array('login' => $login));;
	}
	

}

?>