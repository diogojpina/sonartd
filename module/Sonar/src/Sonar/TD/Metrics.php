<?php

namespace Sonar\TD;

class Metrics {
	private $technicalDebt;
	
	public function __construct($technicalDebt) {
		$this->technicalDebt = $technicalDebt;
	}
	
	public function __call($name, $arguments) {
		$metricName = strtolower($name);
		$metricName = str_replace('get', '', $metricName);
		return $this->getValue($metricName);
	}
	
	private function getValue($metricName) {
		echo $this->technicalDebt->getId() . ' - ';
		echo count($this->technicalDebt->getMeasures()) . " abc\n";
		foreach ($this->technicalDebt->getMeasures() as $measure) {			
			echo $measure->getMetric()->getName() . "\n";
			if ($measure->getMetric()->getName() == $metricName) {
				return $measure->getValue();
			}
		}
		trigger_error("Undefined method $metricName in Metrics", E_USER_ERROR);
	}
	
}

?>