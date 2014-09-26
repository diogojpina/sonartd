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
	
	public function getRoots() {
		return $this->repository->findBy(array('scope' => 'PRJ', 'qualifier' => 'TRK', 'enabled' => 1));
	}
	
	public function getSourceFiles(Project $project) {
		return $this->repository->findBy(array('root' => $project, 'scope' => 'FIL', 'qualifier' => 'FIL', 'enabled' => 1));
	}
}

?>