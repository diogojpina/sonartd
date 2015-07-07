<?php

namespace Sonar\Model;

use Sonar\Entity\Issue;
use Sonar\Entity\Project;

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
		
		$strQuery = "update Sonar\Entity\Issue i 
					INNER JOIN Sonar\Entity\Project p on p.uuid = i.component_uuid
					set i.component_id = p.id
					where i.component_id is null";
		
		/*
		$strQuery = "select i from Sonar\Entity\Issue i
					inner join Sonar\Entity\Project p
					where 1 = 1";
		*/
		
		$query = $this->sm->createQuery($strQuery);
		//$query->getResult();
		$query->execute();
		
		return false;
		$i = 0;
		$n = count($issues);
		foreach ($issues as $issue) {
			echo ++$i . '/' . $n . "\n";
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