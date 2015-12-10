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
	
	public function find(Project $project, $filters) {		
		$where = '';	
		if ($filters['severities']) {			
			$where .= ' and (';			
			$i = 1;
			foreach ($filters['severities'] as $severity) {				
				$where .= " i.severity = '$severity' ";	
				if ($i < count($filters['severities'])) {
					$where .= ' or ';
				}	
				$i++;		
			}
			$where .= ')';
		}
				
		if ($filters['resolutions']) {
			$where .= ' and (';
			$i = 1;
			foreach ($filters['resolutions'] as $resolution) {
				if ($resolution == 'UNRESOLVED') {
					$where .= " i.resolution is null ";
				}
				else {
					$where .= " i.resolution = '$resolution' ";
				}				
				if ($i < count($filters['resolutions'])) {
					$where .= ' or ';
				}
				$i++;
			}
			$where .= ')';			
		}
		else {
			$where .= ' and i.resolution is null';
		}
				
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('i')
   			->from('Sonar\Entity\Issue', 'i')
   			->innerJoin('Sonar\Entity\Project', 'p', 'WITH', 'p.uuid = i.project_uuid')
   			->where('p.uuid = ?1 ' . $where	)
   			->orderBy('i.id', 'ASC')
			->setParameter(1, $project->getUUId());
		
		$query = $qb->getQuery();		
		$results = $query->getResult();		
		return $results;
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