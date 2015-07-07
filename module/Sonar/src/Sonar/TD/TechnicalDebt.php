<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;

abstract class TechnicalDebtCalculator {
	protected $technicalDebt;
	protected $metrics;
	
	abstract public function getCost();
		
	public function __construct(TechnicalDebt $technicalDebt, $metrics) {
		$this->technicalDebt = $technicalDebt;
		$this->metrics = $metrics;
	}
}

?>