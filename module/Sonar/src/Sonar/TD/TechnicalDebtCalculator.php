<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;
use Sonar\TD\Metrics;

abstract class TechnicalDebtCalculator {
	protected $technicalDebt;
	protected $metrics;
	
	abstract public function getCost();
		
	public function __construct(TechnicalDebt $technicalDebt) {
		$this->technicalDebt = $technicalDebt;
		$this->metrics = new Metrics($technicalDebt);
	}
}

?>