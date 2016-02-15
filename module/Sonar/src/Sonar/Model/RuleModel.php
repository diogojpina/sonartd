<?php

namespace Sonar\Model;

use Sonar\Entity\Rule;
use Sonar\Entity\Project;

class RuleModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\Rule');
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\Rule', $id);
	}
	
	public function findByProject(Project $project, $filters) {
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
		
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('r')
		->from('Sonar\Entity\Rule', 'r')
		->innerJoin('r.issues', 'i')
		->innerJoin('i.project', 'p')
		
		->where('p.projectUuid = ?1 ' . $where)
		->orderBy('r.name', 'ASC')
		->groupBy('r.id')
		->setParameter(1, $project->getUUId());
		
		$query = $qb->getQuery();
		$results = $query->getResult();
		
		return $results;
	}
	

}

?>