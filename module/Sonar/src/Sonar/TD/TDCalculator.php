<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;

class TDCalculator {
	private $work_hours = 8;
	
	public function calc(Issue $issue) {
		$key = $issue->getRule()->getPluginRuleKey();
		$className = '\Sonar\TD\\' . $key;
		if (class_exists($className)) {
			$obj = new $className($issue);
			return $obj->getTechnicalDebt();
		}
		else {
			return $issue->getTechnicalDebt();
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