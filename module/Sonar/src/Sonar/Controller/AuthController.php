<?php

namespace Sonar\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Sonar\Model\UserModel;
use Sonar\Model\AuthModel;
use Zend\Authentication\AuthenticationService;

class AuthController extends AbstractActionController {
	
	public function indexAction() {
		$login = isset($_POST['login'])?$_POST['login']:null;
		$pass = isset($_POST['pass'])?$_POST['pass']:null;
		
		if ($login && $pass) {
			$authModel = new AuthModel($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
			$user = $authModel->login($login, $pass);
			if ($user) {
				return $this->redirect()->toRoute('sonar', array('action' => 'list2'));
			}				
		}	
		
	}

}

?>