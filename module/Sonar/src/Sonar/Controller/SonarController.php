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
use Zend\Authentication\AuthenticationService;

class SonarController extends AbstractActionController {
	
	public function indexAction() {
		$auth = new AuthenticationService();
		if (!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('auth');
		}
		 
		$user = $auth->getIdentity();
		
		$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		$tdCalculator = new TDCalculator($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		$tdHelper = new TechnicalDebtHelper($projectModel);
		
		 
		$projects = $projectModel->getRootsByUser($user);

		return array('projects' => $projects);
	}
	
	public function listAction() {
		$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		$tdCalculator = new TDCalculator($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		 
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
    	$auth = new AuthenticationService();
    	if (!$auth->hasIdentity()) {
    		return $this->redirect()->toRoute('auth');
    	}
    	
    	$user = $auth->getIdentity();
    	    	
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdCalculator = new TDCalculator($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdHelper = new TechnicalDebtHelper($projectModel);
 
    	
    	$projects = $projectModel->getRootsByUser($user);
    	
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
    
    public function issuesAction() {
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$userModel = new UserModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	    	
    	$projectId = isset($_GET['project'])?$_GET['project']:0;
    	$project = $projectModel->get($projectId);
    	if (!$project) {
    		return false;
    	}
        	
    	$severities = isset($_GET['severity'])?$_GET['severity']:array();
    	$resolutions = isset($_GET['resolution'])?$_GET['resolution']:array();
    	
    	$filters = array('severities' => $severities, 'resolutions' => $resolutions);
    	
    	$issues = $issueModel->find($project, $filters);
    	
    	$users = $userModel->findAll();
    	
    	return array('project' => $project, 'issues' => $issues, 'users' => $users, 'filters' => $filters);    	
    }
    
    public function showCodeAction() {
    	$config = $this->getServiceLocator()->get('config');
    	$urlAPI = $config['sonar']['urlAPI'];
    	$adminLogin = $config['sonar']['adminLogin'];
    	$adminPass = $config['sonar']['adminPass'];
    	
    	$issueId = $_GET['id'];
    	
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$issue = $issueModel->get($issueId);  

    	if ($issue->getLine() == 0) {
    		$output = '';
    	} else {
    		$kee = $issue->getProject()->getKee();
    		$url = "$urlAPI/api/sources/raw?key=$kee";
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_USERPWD, "$adminLogin:$adminPass");
    		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    		$output = curl_exec($ch);
    		$info = curl_getinfo($ch);
    		curl_close($ch);
    	}
    	
    	$lines = explode("\n", $output);
    	
    	$viewModel = new ViewModel();
    	$viewModel->setVariables(array('lines' => $lines, 'line' => $issue->getLine(), 'quantity' => 10))->setTerminal(true);
    	return $viewModel;
    }
    
    public function calcAction() {
    	$sm = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$projectModel = new ProjectModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$technicalDebtModel = new TechnicalDebtModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$issueModel = new IssueModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	$tdCalculator = new TDCalculator($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	
    	
    	echo "Associando as issues aos componente.\n";
    	$issueModel->updateComponentId();
    	echo "Issues associadas aos componentes.\n\n";
    	
    	
    	/*
    	$issue = $issueModel->get(16704);
    	$technicalDebt = $tdCalculator->calc($issue);
    	return false;
    	*/
    	
    	
    	$iterableResult = $issueModel->findAllIterator();    	
    	foreach ($iterableResult as $row) {
    		$issue = $row[0];
    		$technicalDebt = $tdCalculator->calc($issue);
		}    	
    	return false;
    	
    	
    	$issues = $issueModel->findAll();
    	$sm->clear();
    	
    	$n = count($issues);
    	$i = 0;
    	foreach ($issues as $issue) {
    		$issue2 = $issueModel->get($issue->getId());
    		echo "--" . ++$i . '/' . $n . "\n";
    		$technicalDebt = $tdCalculator->calc($issue2);    		
    	}
    	
    	return false;
    		
    	
    	
   	
    	//$project_id = 587;
    	//$project = $projectModel->get($project_id);
    	$projects = $projectModel->getRoots();
    	foreach ($projects as $project) {
    		echo $project->getName() . "\n";    		
    		$files = $projectModel->getSourceFiles($project);
    		$m = count($files);
    		$j = 0;
    		foreach ($files as $file) {
    			echo $file->getName();
    			echo "--" . ++$j . '/' . $m . "\n";
    			$n = count($file->getIssues());
    			$i = 0;
    			foreach ($file->getIssues() as $issue) {
    				echo "--" . ++$i . '/' . $n . "\n";
    				$technicalDebt = $tdCalculator->calc($issue);
	    		}
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

