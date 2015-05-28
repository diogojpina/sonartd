<?php

namespace Sonar\Model;

use Sonar\Entity\TechnicalDebt;
use Sonar\Entity\Rule;
use Sonar\Entity\Characteristic;
use Sonar\Entity\Issue;

class TechnicalDebtModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\TechnicalDebt');
	}
	
	public function save(TechnicalDebt $technicalDebt) {
		if (!$technicalDebt->getId()) {
			$this->sm->persist($technicalDebt);
		}
		$this->sm->flush();
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
}

?>