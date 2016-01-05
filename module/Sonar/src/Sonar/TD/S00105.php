<?php

namespace Sonar\TD;

class S00105 extends TechnicalDebtCalculator {
	public function getCost() {

		$limit = 100;
		if ($this->metrics->getLines() < $limit) {
			return $this->metrics->getLine();
		}	
		else {
			return $limit + log($this->metrics->getLine());
		}
	}
}

?>