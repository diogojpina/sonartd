<?php

namespace Sonar\Model;

class TechnicalDebtHelper {
	private $projectModel;
	
	public function __construct($projectModel=null) {
		$this->projectModel = $projectModel;
	}
	
	public function getTechnicalDebtProjectFormated($project, $workHours=8) {
		return $this->format($this->getTechnicalDebtProject($project), $workHours);
	}
	
	public function getTechnicalDebtProject($project) {
		$techinicalDebt = 0;
		$files = $this->projectModel->getSourceFiles($project);
		foreach ($files as $file) {
			$techinicalDebt += $this->getTechnicalDebtFile($file);
		}
		return $techinicalDebt;
	}
	
	public function getTechnicalDebtDirFormated($dir, $workHours=8) {
		return $this->format($this->getTechnicalDebtDir($dir), $workHours);
	}
	
	public function getTechnicalDebtDir($dir) {
		$techinicalDebt = 0;
		$files = $this->projectModel->getSourceFilesByFolder($dir);		
		foreach ($files as $file) {
			$techinicalDebt += $this->getTechnicalDebtFile($file);
		}
		return $techinicalDebt;
	}	
	
	public function getTechnicalDebtFileFormated($file, $workHours=8) {
		return $this->format($this->getTechnicalDebtFile($file), $workHours);
	}
	
	
	public function getTechnicalDebtFile($file) {
		$techinicalDebt = 0;

		foreach ($file->getIssues() as $issue) {			
			if (!$issue->getTechnicalDebt()) {
				echo '<h1>Needs to recalculate the technical debt!</h1>'; 
				echo '<br><br>';
			}
			else {
				$techinicalDebt += $issue->getTechnicalDebt()->getTechnicalDebt();
			}
			
		}
		return $techinicalDebt;
	}
	
	public function format($td, $workHours) {
		$td = (int) $td;
		
		if ($td == 0) {
			return 0;
		}
	
		$day = $hour = $min = 0;
		if ($td < 60) {
			$min = $td;
		}
		else if ($td < 60 * $workHours) {
			$min = $td % 60;
			$hour = floor($td / 60);
		}
		else {
			$day = floor($td / ($workHours * 60));
			$resto = $td % ($workHours * 60);
	
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