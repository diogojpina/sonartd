<?php

namespace Sonar\Model;

use Sonar\Entity\Issue;
use Sonar\Entity\TechnicalDebt;

class TechnicalDebtRegression {

	private $selectedMetrics = array('lines', 'ncloc', 'classes', 'functions', 'statements', 'function_complexity', 
							'comment_lines_density', 'duplicated_lines',   
							'violations', 'open_issues');
	private $minIssues = 5;
	
	public function calc($technicalDebts) {
		$debts = array();
		
		foreach ($technicalDebts as $technicalDebt) {
			$debts[] = $technicalDebt;
			/*
			if ($technicalDebt->getIssue()->getRule()->getId() != $technicalOld->getIssue()->getRule()->getId()) {
							
			} 
			*/
			//precisa agrupar por rules, quando a rule mudar chamar o método de calculo
			
			$tdOld = $technicalDebt;
		}
		$this->calcRegression($debts);
		
	}
	
	public function calcRegression($technicalDebts) {
		//se tiver poucas issues parar aqui, pois a regressão ficará ruim
		if (count($technicalDebts) < 5) {
			echo "não tem o suficiente.\n";
			return 0;
		}
		
		foreach ($technicalDebts as $technicalDebt) {
			
		}
	}
		
	public function calc2(TechnicalDebt $technicalDebt) {
		$issue = $technicalDebt->getIssue();
		
		//verificar se a issue já foi finalizada e medida
		
		$metrics = array();
		$rule = $issue->getRule();
		$issues = $rule->getIssues();
			
		//se tiver poucas issues parar aqui, pois a regressão ficará ruim
		if (count($issues) < 5) {
			return 0;
		}
		
		foreach ($issues as $issueAlike) {
			$file = $issueAlike->getProject();
			
			echo $issueAlike->getSeverity();
			
			$tdAlike = $issueAlike->getTechnicalDebt();
			if ($tdAlike == null) echo 'aqui';			
			//$tdMeasures = $tdAlike->getMeasures();
			
		}
		
		return 0;
		if ($issue->getSeverity() == 'CRITICAL') {
		
			$metrics = array();
			$issues = $issue->getRule()->getIssues();
			
			//se tiver poucas issues parar aqui, pois a regressão ficará ruim
			if (count($issues) < 5) {
				return 0;
			}
			
			foreach ($issues as $issueAlike) {				
				$file = $issueAlike->getProject();
					
				$snapshot = $file->getSnapshot();
				$measures = $snapshot->getMeasures();
				
				foreach ($this->selectedMetrics as $idx => $metric) {
					if ($issue->getId() != $issueAlike->getId()) {
						$metrics[$idx][$file->getId()] = 0;
					}
					else {
						$params[$idx] = 0;
					}
				}
					
				foreach ($measures as $measure) {
					$measureName = $measure->getMetric()->getName();
					$idx = array_search($measureName, $this->selectedMetrics);
					if ($idx !== false) {
						if ($issue->getId() != $issueAlike->getId()) {
							$metrics[$idx][$file->getId()] =  $measure->getValue();
						}
						else {
							$params[$idx] =  $measure->getValue();
						}
						//echo $measure->getMetric()->getName() . ' -> ' . $measure->getValue();
						//echo '<br />';
					}

				}
				//echo '<hr />';
				
			}
			echo '<hr />';
			
			
			
			$this->generateRScipt($metrics, $params);
			
			
		}		
			
		
	}
	
	private function generateRScipt($data, $params) {	
		//$file = '/tmp/regression_php' . time() . rand(1, 1000000) . '.r';
		$file = '/tmp/regression_php.r';
		touch($file);
		$fp = fopen($file, 'w');

		
		
		$i = 1;
		$n = count($this->selectedMetrics);
		foreach ($data as $arr) {
			fwrite($fp, "x$i = c(");
			for($j=0; $j < $n; $j++) {
				if (array_key_exists($j, $arr)) {
					fwrite($fp, $arr[$j]);
				}
				else {
					fwrite($fp, '0');
				}
				
				if ($j+1 < $n)
					fwrite($fp, ', ');
			}
			fwrite($fp, ")\n");
			$i++;
		}
		fwrite($fp, "\n");
		
		fwrite($fp, 'mydata = data.frame(');
		for ($i=1; $i <= count($data); $i++) {
			fwrite($fp, "x$i");
			if ($i < count($data))
				fwrite($fp, ', ');
		}
		fwrite($fp, ")\n\n");
		
		$degree = 2;
		$numVars = count($data) - 1;
		$i = 1;
		
		fwrite($fp, "degree = $degree\n");
		fwrite($fp, "numVars = $numVars\n");
		//arrumar
		fwrite($fp, "params = c(");
		for ($i=1; $i <= $numVars; $i++) {
			fwrite($fp, $i);
			if ($i < $numVars)
				fwrite($fp, ', ');
		}	
		fwrite($fp, ")\n\n");
		
		fwrite($fp, 'fit = lm(formula = x' . count($data) . ' ~ ');
		$i = 1;
		while (true) {
			for ($j=1; $j <= $numVars; $j++) {
				fwrite($fp, "I(x$j ^ $i)");
				if ($i < $degree || $j < $numVars) {
					fwrite($fp, ' + ');
				}
			}
		
			$i++;
			if ($i > $degree)
				break;
		}
		fwrite($fp, ", data=mydata)\n\n");

		fwrite($fp, "source('/var/www/html/regression/regression2.include.r')\n");
		
		fclose($fp);	
			
		
		return 0;
		$output = shell_exec("Rscript $file");
		$out_arr = explode(')', $output);
		$output = $out_arr[1];
		$pattern = '/[0-9]+[.]*[0-9]*/';
		preg_match($pattern, $output, $matches);
		$result = $matches[0];
		
		unlink($file);
		
		echo $result;
	}
	
	
}

?>