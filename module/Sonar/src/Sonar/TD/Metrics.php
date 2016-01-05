<?php

namespace Sonar\TD;

class Metrics {
	private $metrics;
	
	public function __construct($technicalDebt) {
		$this->metrics = $technicalDebt->getMetrics();
	}
	
	public function __call($name, $arguments) {
		$metricName = strtolower($name);
		$metricName = str_replace('get', '', $metricName);
		echo $metricName . "\n";
		if(array_key_exists($metricName, $this->metrics) == false) { 		
			trigger_error("Undefined method $name in Metrics", E_USER_ERROR);
		}
		
		return $this->metrics['$metricName']->getValue() . "\n";
	}
	
}

?>