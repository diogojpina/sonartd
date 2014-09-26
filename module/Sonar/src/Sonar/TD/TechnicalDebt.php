<?php

namespace Sonar\TD;

use Sonar\Entity\Issue;

abstract class TechnicalDebt {
	protected $issue;
	
	abstract public function getTechnicalDebt();
		
	public function __construct(Issue $issue) {
		$this->issue = $issue;
	}
}

?>