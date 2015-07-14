<?php

namespace Sonar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Sonar\TD\TDCalculator;
use Sonar\Entity\Project;
use Sonar\Model\ProjectModel;
use Sonar\Model\UserModel;
use Sonar\Model\TechnicalDebtModel;
use Zend\Validator\Isbn;
use Sonar\Model\TechnicalDebtHelper;
use Sonar\Entity\Issue;
use Sonar\Model\IssueModel;
use Sonar\Entity\TechnicalDebtMeasure;
use Sonar\Model\TechnicalDebtMeasureModel;
use Sonar\Model\CharacteristicModel;
use DoctrineORMModule\Proxy\__CG__\Sonar\Entity\Rule;
use Zend\View\Model\JsonModel;

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
				echo "<h3>" . $file->getName() . $file->getId() ."</h3><br />";
	
				$sum = 0;
				foreach ($file->getIssues() as $issue) {
					$td = $issue->getTechnicalDebt()->getTechnicalDebt();
					echo $issue->getTechnicalDebt()->getTechnicalDebtFormated();
					//echo $tdCalculator->format($td);
					echo "<br />";
					$sum += $td;
				}
				echo "Soma: " . $tdCalculator->format($sum);
				echo '<hr />';
			}
		}
		return false;
	}	
    
    
    
    public function list2Action() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdCalculator = new TDCalculator();
    	$tdHelper = new TechnicalDebtHelper($projectModel);
 
    	
    	$projects = $projectModel->getRoots();
    	
    	$project_id = isset($_GET['project'])?$_GET['project']:0;
    	$file_id = isset($_GET['file'])?$_GET['file']:0;
    	$dir_id = isset($_GET['dir'])?$_GET['dir']:0;
    	
    	$project = null;
    	if ($project_id) {
    		$project = $projectModel->get($project_id); 
    	}
    	
    	
    	
    	$files = null;
    	$dirs = array();
    	$dir = null;
    	if ($project) {
    		$files = $projectModel->getSourceFiles($project);
    		$dir = array();
    		foreach ($files as $f) {
    			$directory = $projectModel->getByLongName($f->getRoot(), $f->getDirName());
    			if ($directory) {
    				$dirs[$directory->getName()] = $directory;
    			}
    		}
    		
    		if ($dir_id) {
    			$dir = $projectModel->get($dir_id);
    			$files = $projectModel->getSourceFilesByFolder($dir);
    		}
    		else {
    			$dir = new Project();
    		}
    		
    	}
    	
    	
    	$file = null;
    	if ($file_id) {
    		$file = $projectModel->get($file_id);
    	}
    	else {
    		$file = new Project();
    	}
    	
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$userModel = new UserModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$users = $userModel->findAll();
    	
    	
    	 
    	
    	$data = array();    	
    	$data['projects'] = $projects;
    	$data['project'] = $project;
    	$data['dirs'] = $dirs;
    	$data['dir'] = $dir;
    	$data['files'] = $files;
    	$data['file'] = $file;
    	$data['users'] = $users;
    	$data['tdHelper'] = $tdHelper;
    	
    	return $data;
    	
    }
    
    public function calcAction() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$technicalDebtModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdCalculator = new TDCalculator();
    	
    	
    	
    	$project_id = 587;
    	$project = $projectModel->get($project_id);
    	$files = $projectModel->getSourceFiles($project);    	
    	foreach ($files as $file) {
    		foreach ($file->getIssues() as $issue) {
    			$technicalDebt = $tdCalculator->calc($issue);
    			
    			
    			//$technicalDebtModel->save($technicalDebt);
    		}
    	}
    	
    	
    	return false;
    	
    	//FIX
    	$issueModel->updateComponentId();   	
    	    	    	
    	$projects = $projectModel->getRoots();    	
    	foreach ($projects as $project) {
    		$files = $projectModel->getSourceFiles($project);
    		foreach ($files as $file) {
    			foreach ($file->getIssues() as $issue) {
    				$technicalDebt = $tdCalculator->calc($issue);
    				$technicalDebtModel->save($technicalDebt);    				
    			}
    		}
    	}
    	return false;
    }
    
    public function assignTDAction() {
    	$userModel = new UserModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));

		$id = (int) $this->params()->fromPost('id');
    	$login = $this->params()->fromPost('login');
		
    	$issue = $issueModel->get($id);
    	$user = $userModel->getByLogin($login);
		
    	$issue->setAssignee($user->getLogin());
    	$issueModel->save($issue);
    	
        return $this->response;
    }
    
    
    //OPEN, REOPENED e CONFIRMED (precisa pagar)
    //CLOSED + FIXED (paga)
    //CLOSED + FALSE-POSITIVE (desconsiderar a dívida)
    
    public function payTDAction() {
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$technicalDebtModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$technicalDebtMeasureModel = new TechnicalDebtMeasureModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    
    	$issueId = isset($_POST['id'])?$_POST['id']:0;
    	$realTD = isset($_POST['realTD'])?$_POST['realTD']:0;
    
    	$issue = $issueModel->get($issueId);
    	
    	if ($realTD) {
    		$project = $issue->getProject();
    		$snapshot = $project->getSnapshot();
    		$measures = $snapshot->getMeasures();
    		$directory = $projectModel->getByLongName($project->getRoot(), $project->getDirName());
    		 
    		$technicalDebt = $issue->getTechnicalDebt();
    		$technicalDebtMeasures = $technicalDebt->getMeasures();
    		 
    		$technicalDebtMeasureModel->deleteByTechnicalDebt($technicalDebt);
    		 
    		foreach ($measures as $measure) {
    			$technicalDebtMeasure = new TechnicalDebtMeasure();
    			$technicalDebtMeasure->setMetric($measure->getMetric());
    			$technicalDebtMeasure->setTechnicalDebt($technicalDebt);
    			$technicalDebtMeasure->setValue($measure->getValue());
    
    			$technicalDebtMeasureModel->save($technicalDebtMeasure);
    		}
    		 
    		$technicalDebt->setRealTD($realTD);
    		$technicalDebtModel->save($technicalDebt);
    	}
    	
    	return $this->response;
    }    
    
    public function commentTDAction() {
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	 
    	$id = isset($_POST['id'])?$_POST['id']:0;
    	$comment = isset($_POST['comment'])?$_POST['comment']:0;
    	
    	$issue = $issueModel->get($id);
    	//$issueModel->save($issue);
    	
    	return $this->response;
    }
    
    public function changeSeverityAction() {
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	$id = isset($_POST['id'])?$_POST['id']:0;
    	$severity = isset($_POST['severity'])?$_POST['severity']:0;
    	
    	$issue = $issueModel->get($id);
    	$issue->setSeverity($severity);
    	$issue->setManualSeverity(1);
    	$issueModel->save($issue);    	    	
    	
    	return $this->response;
    }
    
    public function changeStatusAction() {
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	 
    	$id = isset($_POST['id'])?$_POST['id']:0;
    	$statusAction = isset($_POST['status'])?$_POST['status']:0;
    	
    	$resolution = '';
    	switch ($statusAction) {
    		case 'confirm':
    			$status = 'CONFIRMED';
    			break;
    		case 'unconfirm':
    			$status = 'REOPENED';
    			break;
    		case 'fix':
    			$status = 'RESOLVED';
    			$resolution = 'FIXED';
    			break;
    		case 'fix_not':
    			$status = 'RESOLVED';
    			$resolution = 'WONTFIX';
    			break;
    		case 'false_positive':
    			$status = 'RESOLVED';
    			$resolution = 'FALSE-POSITIVE';
    		case 'reopen':
    			$status = 'REOPENED';
    			break;
    	}
    	
    	 
    	$issue = $issueModel->get($id);
    	$issue->setStatus($status);
    	$issue->setResolution($resolution);
    	$issueModel->save($issue);
    	 
    	return $this->response;    	
    }
    
    public function characteristicAction() {
    	$characteristicModel = new CharacteristicModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	$characteristics = $characteristicModel->getRoots();
    	
    	foreach ($characteristics as $characteristic) {
    		echo $characteristic->getName();
    		echo '<br />';
    		
    		foreach ($characteristic->getSubCharacteristics() as $subcharacteristic) {
    			echo '-> ' . $subcharacteristic->getName();
    			echo '<br />';
    			
    			foreach ($subcharacteristic->getRules() as $rule) {
    				echo '-> -> ' . $rule->getName();
    				echo '<br />';
    			}
    		}
    		echo '<hr />';
    	}
    	
    	
    	
    	
    	return false;
    }
    
    public function pyramidh5Action() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	 
    	$id = isset($_GET['project'])?$_GET['project']:0;
    	
    	$project = $projectModel->get($id);
    	 
    	$data = $tdModel->getByRisk($project);
    	 
    	$data['label'] = array('Acumulado', 'TD');
    	
    	return $data;
    }
    
    public function pyramidAction() {
    	
    }
    
    public function pyramidJsonAction() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	$id = isset($_GET['project'])?$_GET['project']:0;

    	$project = $projectModel->get($id);
    	
    	$data = $tdModel->getByRisk($project);
    	
    	$data['label'] = array('Acumulado', 'TD');
    	
    	return new JsonModel($data);
    }
    
    
    
    public function categorizationAction() {
    	$characteristicModel = new CharacteristicModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	
    	$characteristics = $characteristicModel->getRoots();
    	
    	foreach ($characteristics as $characteristic) {
    		echo $characteristic->getName();
    		echo '<br />';
    		
    		$td = $tdModel->getByCharacteristic($characteristic);
    		echo "TD = $td min";
    		echo '<hr />';
    	}
    	
    	return false;
    }
    
    public function sqaleRateAction() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	$file_id = 589;
    	$file = $projectModel->get($file_id);
    	    	
    	$rate = $tdModel->getFileRate($file);
    	
    	print_r($rate);
    	
    	return false;
    }

    public function sunburstAction() {
    
    }
    
    public function areachartAction() {
    
    }
    
    public function timelineAction() {
    	
    }
}

