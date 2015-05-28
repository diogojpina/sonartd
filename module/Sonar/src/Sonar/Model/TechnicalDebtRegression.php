<?php

namespace Sonar\Model;

use Sonar\Entity\Issue;

class TechnicalDebtRegression {
	
	public function calc(Issue $issue) {
		//verificar se a issue já foi finalizada e medida
		
		
		
		if ($issue->getSeverity() == 'CRITICAL') {
		
		$issues = $issue->getRule()->getIssues();
		foreach ($issues as $issueAlike) {
			if ($issue->getId() != $issueAlike->getId()) {
				echo $issueAlike->getMessage();
				echo '<br />';
			}
		}
		echo '<hr />';
		}
		

		
	}
}

?>