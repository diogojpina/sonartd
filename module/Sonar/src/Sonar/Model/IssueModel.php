<?php

namespace Sonar\Model;

use Sonar\Entity\Issue;
use Sonar\Entity\Project;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
	
	public function findAll() {
		return $issues = $this->repository->findAll();
	}
	
	public function findAllIterator() {
		$query = $this->sm->createQuery('select i from Sonar\Entity\Issue i');
		return $query->iterate();
	}
	
	public function findIterator(Project $project) {
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('i')
		->from('Sonar\Entity\Issue', 'i')
		->innerJoin('Sonar\Entity\Project', 'p', 'WITH', 'p.uuid = i.project_uuid')
		->where('p.uuid = ?1 ')
		->orderBy('i.id', 'ASC')
		->setParameter(1, $project->getUUId());
		
		$query = $qb->getQuery();
		
		return $query->iterate();
	}
	
	public function find(Project $project, $filters, $limit=30, $page=1) {		
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
		
		if ($filters['users']) {
			$where .= ' and (';
			$i = 1;
			foreach ($filters['users'] as $user) {
				$where .= " i.assignee = '$user' ";
				if ($i < count($filters['users'])) {
				$where .= ' or ';
				}
				$i++;
			}
			$where .= ')';
		}	

		if ($filters['rules']) {
			$where .= ' and (';
			$i = 1;
			foreach ($filters['rules'] as $rule) {
				$where .= " r.id = $rule ";
				if ($i < count($filters['rules'])) {
					$where .= ' or ';
				}
				$i++;
			}
			$where .= ')';
		}		
		
		if ($filters['folder']) {
			$projectModel = new ProjectModel($this->sm);
			$folder = $projectModel->get($filters['folder']);
			$files = $projectModel->getSourceFilesByFolder($folder);
			$newFiles = array();
			if (count($filters['files']) == 0) {
				foreach ($files as $file) {
					$newFiles[] = $file->getId();
				}
			}
			else {
				foreach ($files as $file) {
					if (in_array($file->getId(), $filters['files'])) {
						$newFiles[] = $file->getId();
					}
				}
			}	
			$filters['files'] = $newFiles;		
		}
		
		
		if ($filters['files']) {
			$where .= ' and (';
			$i = 1;
			foreach ($filters['files'] as $file) {
				$where .= " f.id = $file ";
				if ($i < count($filters['files'])) {
					$where .= ' or ';
				}
				$i++;
			}
			$where .= ')';
		}
		
		$first = ($page - 1) * $limit;
						
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('i')
   			->from('Sonar\Entity\Issue', 'i')
   			->innerJoin('Sonar\Entity\Project', 'p', 'WITH', 'p.uuid = i.project_uuid')
   			->innerJoin('Sonar\Entity\Project', 'f', 'WITH', 'f.id = i.component_id')
   			->innerJoin('i.rule', 'r')
   			->where('p.uuid = ?1 ' . $where	)
   			->orderBy('f.id', 'ASC')
   			->setFirstResult($first)
   			->setMaxResults($limit)
			->setParameter(1, $project->getUUId());

		$paginator = new Paginator($qb);		
		$npages = ceil(count($paginator) / $limit);
		
		
		$query = $qb->getQuery();
		$results = $query->getResult();		
		return array('issues' => $results, 'npages' => $npages);
	}
	
	private function findWithOutComponent() {
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('i')
		->from('Sonar\Entity\Issue', 'i')
		->where('i.component_id is null');
		
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
		$issues = $this->findWithOutComponent();

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
		}		
		$this->sm->flush();
	}
}

?>