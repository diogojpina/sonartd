<?php

namespace Sonar\Model;

use Sonar\Entity\Project;
use Sonar\Entity\GroupRoles;
use Doctrine\ORM\QueryBuilder;

class ProjectModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;	
		$this->repository = $sm->getRepository('Sonar\Entity\Project');
	}
	
	public function findAll() {
		return $this->repository->findAll();
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\Project', $id);
	}
	
	public function getByUUID($uuid) {
		return $projects = $this->repository->findOneBy(array('uuid' => $uuid));
	}	
	
	public function getRoots() {
		return $this->repository->findBy(array('scope' => 'PRJ', 'qualifier' => 'TRK', 'enabled' => 1));
	}
	
	public function getRootsByUser($user) {
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('p')
		->from('Sonar\Entity\Project', 'p')
		//->leftJoin('Sonar\Entity\GroupRoles', 'gr', 'WITH', 'p.id = gr.project')
		->leftJoin('p.groupRoles', 'gr')
		//->leftJoin('Sonar\Entity\Group', 'g', 'WITH', 'g.id = gr.group')
		->leftJoin('gr.group', 'g')
		->leftJoin('g.users', 'u1')
		
		->leftJoin('p.userRoles', 'ur')		
		->leftJoin('ur.user', 'u2')
		
		->where('p.scope = ?1 and p.qualifier = ?2 and p.enabled = ?3 and gr.role = ?4 and (u1.id = ?5 or u2.id = ?5 or u1.id is null)')
		->orderBy('p.name', 'ASC')
		->groupBy('p.id')
		->setParameter(1, 'PRJ')
		->setParameter(2, 'TRK')
		->setParameter(3, 1)
		->setParameter(4, 'user')
		->setParameter(5, $user->getId());
		
		$query = $qb->getQuery();
		$results = $query->getResult();
		return $results;
	}
	
	public function getSourceFiles(Project $project) {
		return $this->repository->findBy(array('root' => $project, 'scope' => 'FIL', 'qualifier' => 'FIL', 'enabled' => 1));
	}
	
	public function getSourceFilesByFolder(Project $folder) {
		$query = $this->sm->createQuery("select p from \Sonar\Entity\Project p where p.scope='FIL' and p.qualifier = 'FIL' and p.enabled = 1 and p.longName like '" . $folder->getName() . "%'");
		$projects = $query->getResult();
		return $projects;		
	}
	
	public function getFolderWithIssues(Project $project) {
		
	}
	
	public function getFilesWithIssues(Project $project, Project $folder) {
		//return $this->repository->findBy(array('root' => $project, 'scope' => 'FIL', 'qualifier' => 'FIL', 'enabled' => 1));
	}
	
	public function getByLongName(Project $root, $longName) {
		$projects = $this->repository->findBy(array('root' => $root, 'longName' => $longName));
		foreach ($projects as $project) {
			return $project;
		}
	}
}

?>