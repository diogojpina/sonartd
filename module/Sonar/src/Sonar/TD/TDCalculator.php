<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;
use Sonar\Model\TechnicalDebtRegression;

class TDCalculator {
	private $work_hours = 8;
	
	
	public function calc(Issue $issue) {
		
		if ($issue->getTechnicalDebt()) {
			$technicalDebt = $issue->getTechnicalDebt();
		} 
		else {
			$technicalDebt = new TechnicalDebt();
			$technicalDebt->setIssue($issue);
		}
		
		
		$key = $issue->getRule()->getPluginRuleKey();
		$className = '\Sonar\TD\\' . $key;
		if (class_exists($className)) {
			$obj = new $className($issue);
			$technicalDebt->setModelTD($obj->getTechnicalDebt());
		}
		else {
			$technicalDebt->setModelTD(0);
		}
		
		$technicalDebtRegression = new TechnicalDebtRegression();
		$technicalDebtRegression->calc($issue);
		
		$technicalDebt->setRegressionTD(0);	
		if (!$technicalDebt->getRealTD()) {
			$technicalDebt->setRealTD(0);
		}
		
		$technicalDebt->setSonarTD($issue->getTD());
		
		return $technicalDebt;
	}
	
	public function format($td) {
		$td = (int) $td;
		
		$day = $hour = $min = 0;
		if ($td < 60) {
			$min = $td;
		} 
		else if ($td < 60 * $this->work_hours) {
			$min = $td % 60;
			$hour = floor($td / 60);
		}
		else {
			$day = floor($td / ($this->work_hours * 60));
			$resto = $td % ($this->work_hours * 60);
			
			$hour = floor($resto / 60);
			$min = $resto % 60;
		}
		
		$time = '';
		if ($day) {
			$time .= "$day days ";
		}
		
		if ($hour) {
			$time .= "$hour hour ";
		}
		
		if ($min) {
			$time .= "$min mins ";
		}
		
		return trim($time);				
	}
}

?>