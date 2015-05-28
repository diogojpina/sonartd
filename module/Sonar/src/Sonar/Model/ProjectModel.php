<?php

namespace Sonar\Model;

use Sonar\Entity\Project;

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
		return$projects = $this->repository->findOneBy(array('uuid' => $uuid));
	}	
	
	public function getRoots() {
		return $this->repository->findBy(array('scope' => 'PRJ', 'qualifier' => 'TRK', 'enabled' => 1));
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