<?php

namespace Sonar\TD;

use Sonar\Model\Issue;

abstract class TD {
	protected $issue;
	
	abstract public function getTD();
		
	public function __construct(Issue $issue) {
		$this->issue = $issue;
	}
}

?>