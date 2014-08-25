<?php

namespace Sonar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Sonar\Model\Project;
use Sonar\Model\ProjectDao;
use Sonar\Model\Issue;
use Sonar\Model\IssueDao;
use Sonar\TD\TDCalculator;

class SonarController extends AbstractActionController {
	
	public function indexAction() {
		$projectDao = new ProjectDao ( $this->getServiceLocator () );
		$projects = $projectDao->fetchAll ();
		
		$project = $projectDao->get ( 1 );
		print_r ( $project );
		echo '<hr />';
		foreach ( $projects as $project ) {
			print_r ( $project );
			echo '<br />';
		}
		return new ViewModel ();
	}

    public function listAction() {
    	/*
    	$project = new Project();
    	$project->id = 29;
    	
        $issueDao = new IssueDao($this->getServiceLocator());
        
        $issues = $issueDao->getIssuebyProject($project);
        
        foreach ($issues as $issue) {
        	$tdCalculator = new TDCalculator();
        	$td = $tdCalculator->calc($issue);
        	echo $td;
        	echo '<br><br>';
        }
        */
    	$projectDao = new ProjectDao($this->getServiceLocator());
    	$issueDao = new IssueDao($this->getServiceLocator());
    	$tdCalculator = new TDCalculator();
    	
    	$projects = $projectDao->getRoots();    	
    	foreach ($projects as $project) {
    		$files = $projectDao->getSourceFiles($project);
    		foreach ($files as $file) {
    			echo "<h3>$file->name</h3><br />";
    			$issues = $issueDao->getIssuebyProject($file);
    			$sum = 0;
    			foreach ($issues as $issue) {
    				$td = $tdCalculator->calc($issue);
    				//print_r($issue);
    				echo $tdCalculator->format($td);
    				echo "<br />";
    				$sum += $td;    				    				
    			}
    			echo "Soma: " . $tdCalculator->format($sum);
    			echo '<hr />';
    		}
    	}
    	
    	
    	
    	return new ViewModel();
    }



}

