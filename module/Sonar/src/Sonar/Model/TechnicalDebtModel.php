<?php

namespace Sonar\Model;

use Sonar\Entity\TechnicalDebt;
use Sonar\Entity\Rule;
use Sonar\Entity\Characteristic;
use Sonar\Entity\Issue;
use Sonar\Entity\Project;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;

class TechnicalDebtModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\TechnicalDebt');
	}
	
	public function refresh(TechnicalDebt $technicalDebt) {
		$this->sm->refresh($technicalDebt);
	}
	
	public function save(TechnicalDebt $technicalDebt) {
		if (!$technicalDebt->getId()) {
			$this->sm->persist($technicalDebt);
		}
		$this->sm->flush();
	}
	
	public function findPayed(Project $project) {
		$qb = $this->sm->createQueryBuilder();
		$qb	->select('td')
		->from('Sonar\Entity\TechnicalDebt', 'td')
		->innerJoin('td.issue', 'i')
		->innerJoin('i.rule', 'r')		
		->innerJoin('Sonar\Entity\Project', 'p', 'WITH', 'p.uuid = i.project_uuid')
		->where('p = ?1 and td.realTD is not null')
		->orderBy('r.id', 'ASC')
		->setParameter(1, $project);
		
		$query = $qb->getQuery();
		
		$results = $query->getResult();
		return $results;
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\TechnicalDebt', $id);
	}
	
	public function getByProject(Project $project) {
		$projectModel = new ProjectModel($this->sm);
		
		$tds = array();
		
		$files = $projectModel->getSourceFiles($project);
		foreach ($files as $file) {
			$issues = $file->getIssues();
			foreach ($issues as $issue) {
				$tds[] = $issue->getTechnicalDebt();
			}			
		}
		
		return $tds;		
	}
	
	public function getByRisk(Project $project) {
		$tds = $this->getByProject($project);
		
		$categories = array('INFO', 'MINOR', 'MAJOR', 'CRITICAL', 'BLOCKER');
		foreach ($categories as $categorie) {
			$data[$categorie] = 0; 
		}
		
		$total = 0;
		
		foreach ($tds as $td) {
			$data[$td->getIssue()->getSeverity()] += $td->getTechnicalDebt();
			$total += $td->getTechnicalDebt();			
		}
		
		$accumulated = 0;
		$accumulatedTotal = 0;
		foreach ($categories as $categorie) {
			$accumulatedTotal += $data[$categorie];
			$percent = $data[$categorie] * 100 / $total;
			$data['values'][] = array('label' => $categorie, 'values' => array($accumulated, $percent, $data[$categorie], $accumulatedTotal));			
			$accumulated += $percent;			
		}		
		
		return $data;
	}
	
	public function getByCharacteristic(Characteristic $characteristic) {
		$sum = 0;
		foreach ($characteristic->getSubCharacteristics() as $subCharacteristic) {
			$sum += $this->getBySubCharacteristic($subCharacteristic);
		}
		return $sum;
	}
	
	public function getBySubCharacteristic(Characteristic $subCharacteristic) {
		$sum = 0;
		foreach ($subCharacteristic->getRules() as $rule) {
			$sum += $this->getByRule($rule);
		}	
		return $sum;
	}

	
	public function getByRule(Rule $rule) {
		$sum = 0;
		foreach ($rule->getIssues() as $issue) {
			$sum += $this->getByIssue($issue);
		}
		return $sum;
	}	
	
	public function getByIssue(Issue $issue) {
		$td = $issue->getTechnicalDebt();
		
		if ($td)
			return $td->getTechnicalDebt();
	}
	
	public function getByFile(Project $file) {
		$sum = 0;
		foreach ($file->getIssues() as $issue) {
			$sum += $this->getByIssue($issue);
		}
		return $sum;
	}
	
	
	public function getFileRate(Project $file) {
		$td = $this->getByFile($file);
		
		$costLine = 0.48;
		$nlines = 200;
		
		$density = $this->getDensity($td, $costLine, $nlines);
		
		$rate = $this->getRate($density);
		
		$effortToImproveRate = $this->getEffortToImproveRate($density, $td, $costLine, $nlines);
		
		
		$obj = new \stdClass();
		$obj->density = $density;
		$obj->rate = $rate;
		$obj->efforToImproveRate = $effortToImproveRate;
		
		return $obj;
	}
	
	private function getDensity($td, $costLine, $nlines) {
		return $td / ($costLine * $nlines);
	}
	
	private function getImproveDensity($density, $td, $costLine, $nLines) {
		$tdm = $density * $costLine * $nLines;
		return $td - $tdm;
	}
	
	private function getRate($density) {
		if ($density <= 0.1)
			return 'A';
		else if ($density > 0.1 && $density <= 0.2)
			return 'B';
		else if ($density > 0.2 && $density <= 0.5)
			return 'C';					
		else if ($density > 0.5 && $density <= 1)
			return 'D';
		else
			return 'E';
	}
	
	
	private function getEffortToImproveRate($density, $td, $costLine, $nLines) {		
		$effort = array();
		if ($density > 0.1)
			$effort['A'] = $this->getImproveDensity(0.1, $td, $costLine, $nLines);
		
		if ($density > 0.2)
			$effort['B'] = $this->getImproveDensity(0.2, $td, $costLine, $nLines);					
		
		if ($density > 0.5)
			$effort['C'] = $this->getImproveDensity(0.5, $td, $costLine, $nLines);
		
		if ($density > 1) 
			$effort['D'] = $this->getImproveDensity(1, $td, $costLine, $nLines);


		return $effort;
	}
	
	
	
}

?>