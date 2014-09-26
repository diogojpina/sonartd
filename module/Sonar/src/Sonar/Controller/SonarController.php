<?php

namespace Sonar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Sonar\Model\Project;
use Sonar\Model\ProjectDao;
use Sonar\Model\ProjectModel;
use Sonar\Model\Issue;
use Sonar\Model\IssueDao;
use Sonar\TD\TDCalculator;

class SonarController extends AbstractActionController {
	
	public function indexAction() {
		$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		
		$repository = $objectManager->getRepository('Sonar\Entity\Project');
		$projects = $repository->findAll();
		
		foreach ( $projects as $project ) {
			print_r ( $project );
			echo '<br />';
		}
		return new ViewModel ();
	}
    
    public function listAction() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdCalculator = new TDCalculator();
    	
    	$projects = $projectModel->getRoots();
    	foreach ($projects as $project) {
    		$files = $projectModel->getSourceFiles($project);
    		foreach ($files as $file) {
    			echo "<h3>" . $file->getName() . "</h3><br />";
    			
    			$sum = 0;
    			foreach ($file->getIssues() as $issue) {
    				$td = $tdCalculator->calc($issue);
    				echo $tdCalculator->format($td);
    				echo "<br />";
    				$sum += $td;    				
    			}
    			echo "Soma: " . $tdCalculator->format($sum);
    			echo '<hr />';
    		}
    	}
    	
    	return false;
    }



}

