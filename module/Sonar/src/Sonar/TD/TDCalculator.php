<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;
use Sonar\Model\TechnicalDebtRegression;
use Sonar\Entity\TechnicalDebtMeasure;
use Sonar\Model\TechnicalDebtMeasureModel;

class TDCalculator {
	private $work_hours = 8;
	private $sm;
	
	public function __construct($sm) {
		$this->sm = $sm;
	}
	
	
	public function calc(Issue $issue) {
		if ($issue->getTechnicalDebt()) {
			$technicalDebt = $issue->getTechnicalDebt();			
		} 
		else {
			$technicalDebt = new TechnicalDebt();
			$technicalDebt->setIssue($issue);
		}
		
		
		if ($issue->getStatus() == 'OPEN' || $issue->getStatus() == 'CONFIRMED') {
			$tdMeasureModel = new TechnicalDebtMeasureModel($this->sm);
			$metrics = $technicalDebt->getMetrics();
			
			echo $issue->getProject()->getId() . "\n";
			echo $technicalDebt->getId() . "\n";
			
			echo count($metrics) . "\n";
			
			
			$measures = $issue->getProject()->getSnapshot()->getMeasures();
			
			foreach ($measures as $measure) {
				echo $measure->getMetric()->getId() . " - ";
				echo $measure->getMetric()->getName() . "\n";
				
				$update = true;
				foreach ($metrics as $metric) {
					print_r($measure->getRule());
					if ($measure->getMetric()->getId() == $metric->getMetric()->getId() && 
						$measure->getRule()->getId() == $metric->getRule()->getId()) {
						echo $measure->getValue() . " - " . $metric->getValue() . "\n";
						if ($measure->getValue() == $metric->getValue()) {
							$update = false;
						}
						break;
					}															
				}
				
				if ($update) {
					echo "atualizar\n";
				}				
			}
			sleep(5);
			
			
			
			$tdMeasureModel->deleteByTechnicalDebt($technicalDebt);
			foreach ($measures as $measure) {
				$technicalDebtMeasure = new TechnicalDebtMeasure();
				$technicalDebtMeasure->setMetric($measure->getMetric());
				$technicalDebtMeasure->setTechnicalDebt($technicalDebt);
				$technicalDebtMeasure->setValue($measure->getValue());
				echo $measure->getMetric()->getId() . " - ";
				echo $measure->getMetric()->getName() . "\n";
		
				$tdMeasureModel->save($technicalDebtMeasure);
			}
			echo "aqui\n\n";
		}
		else {
			return false;
		}		

		
		
		$technicalDebt->setSonarTD($issue->getTD());
		
		$this->calcByModelClass($technicalDebt);
		
		return false;
		
		//calculate using regression
		$technicalDebtRegression = new TechnicalDebtRegression();
		$technicalDebtRegression->calc($technicalDebt);
		
		$technicalDebt->setRegressionTD(0);	
		if (!$technicalDebt->getRealTD()) {
			$technicalDebt->setRealTD(0);
		}
		
		
		
		return $technicalDebt;
	}
	
	private function calcByModelClass(TechnicalDebt $technicalDebt) {
		$issue = $technicalDebt->getIssue();				
		$key = $issue->getRule()->getPluginRuleKey();
		
		$className = '\Sonar\TD\\' . $key;
		if (class_exists($className)) {
			$obj = new $className($technicalDebt);
			$technicalDebt->setModelTD($obj->getCost());
		}
		else {
			$technicalDebt->setModelTD(0);
		}
		
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