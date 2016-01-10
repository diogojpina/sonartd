<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;
use Sonar\Model\TechnicalDebtRegression;
use Sonar\Entity\TechnicalDebtMeasure;
use Sonar\Model\TechnicalDebtMeasureModel;
use Sonar\Model\TechnicalDebtModel;

class TDCalculator {
	private $work_hours = 8;
	private $sm;
	
	public function __construct($sm) {
		$this->sm = $sm;
	}
	
	public function calc(Issue $issue) {
		$technicalDebtModel = new TechnicalDebtModel($this->sm);
		
		echo "ID: " . $issue->getId() . "\n";
		//echo $issue->getRule()->getPluginRuleKey() . "\n";
		
		if ($issue->getTechnicalDebt()) {
			$technicalDebt = $issue->getTechnicalDebt();			
		} 
		else {
			$technicalDebt = new TechnicalDebt();
			$technicalDebt->setIssue($issue);
			$technicalDebtModel->save($technicalDebt);
		}

		//echo "Setting measures\n";
		$this->setMeasures($technicalDebt);		
		//echo "Setted measures\n";
		
		//$technicalDebt = $technicalDebtModel->get($technicalDebt->getId());
		$technicalDebtModel->refresh($technicalDebt);
		
		$technicalDebt->setSonarTD($issue->getTD());
		
		echo count($technicalDebt->getMeasures()) . "\n";
		$this->calcByModelClass($technicalDebt);
		
		$technicalDebtModel->save($technicalDebt);
		
		return $technicalDebt;
		
		//calculate using regression
		$technicalDebtRegression = new TechnicalDebtRegression();
		$technicalDebtRegression->calc($technicalDebt);
		
		$technicalDebt->setRegressionTD(0);	
		if (!$technicalDebt->getRealTD()) {
			$technicalDebt->setRealTD(0);
		}
		
		
		
		return $technicalDebt;
	}
	
	private function isSameMeasure($measure1, $measure2) {
		if ($measure1->getMetric()->getId() == $measure2->getMetric()->getId()) {
			if ($measure1->getRule() && $measure2->getRule()) {
				if ($measure1->getRule()->getId() == $measure2->getRule()->getId()) {
					return true;
				}
			}
			else if ($measure1->getCharacteristic() && $measure2->getCharacteristic()) {
				if ($measure1->getCharacteristic()->getId() == $measure2->getCharacteristic()->getId()) {
					return true;
				}
			}
			else if (($measure1->getRule() == null && $measure2->getRule() == null) &&
			($measure1->getCharacteristic() == null && $measure2->getCharacteristic() == null)) {
				return true;
			}
		}
		return false;
	}	
	
	private function setMeasures(TechnicalDebt $technicalDebt) {
		$issue = $technicalDebt->getIssue();
		if ($issue->getStatus() == 'OPEN' || $issue->getStatus() == 'CONFIRMED') {
			$tdMeasureModel = new TechnicalDebtMeasureModel($this->sm);
			
			/*
			echo $issue->getProject()->getId() . "\n";
			echo $issue->getProject()->getSnapshot()->getId() . "\n";
			echo $technicalDebt->getId() . "\n";				
			*/
				
				
			$measures = $issue->getProject()->getSnapshot()->getMeasures();
			$tdMeasures = $technicalDebt->getMeasures();
			//echo $issue->getProject()->getId() . "\n";		
			//echo count($measures) . "\n";
				
			foreach ($measures as $measure) {
				/*
				echo $measure->getId() . " - ";
				echo $measure->getMetric()->getId() . " - ";
				echo $measure->getMetric()->getName() . "\n";
				*/
		
				$update = true;
				$tdMeasureToUpdate = null;
				foreach ($tdMeasures as $tdMeasure) {
					if ($this->isSameMeasure($measure, $tdMeasure) == true) {
						if ($measure->getValue() == $tdMeasure->getValue()) {
							$tdMeasureToUpdate = $tdMeasure;
							$update = false;
						}
						break;
					}
				}
		
				if ($update) {
					if ($tdMeasureToUpdate == null) {
						$tdMeasureToUpdate = new TechnicalDebtMeasure();
						$tdMeasureToUpdate->setTechnicalDebt($technicalDebt);
						$tdMeasureToUpdate->setMetric($measure->getMetric());
						$tdMeasureToUpdate->setRule($measure->getRule());
						$tdMeasureToUpdate->setCharacteristic($measure->getCharacteristic());
					}
						
					$tdMeasureToUpdate->setValue($measure->getValue());
					$tdMeasureModel->save($tdMeasureToUpdate);
						
					//echo "atualizar\n";
				}
			}
		}		
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