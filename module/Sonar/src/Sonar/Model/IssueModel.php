<?php

namespace Sonar\Model;

use Sonar\Entity\Issue;

class IssueModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\Issue');
	}
	
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\Issue', $id);
	}
	
	public function save(Issue $issue) {
		if (!$issue->getId()) {
			$this->sm->persist($issue);
		}
		$this->sm->flush();
	}
	
	public function updateComponentId() {
		$projectModel = new ProjectModel($this->sm);
		$issues = $this->repository->findAll();
		
		foreach ($issues as $issue) {
			if ($issue->getProject()) {
				continue;
			}
			$uuid = $issue->getComponentUUID();
			$project = $projectModel->getByUUID($uuid);			

			$issue->setProject($project);
			$this->save($issue);			
		}		
	}
}

?>