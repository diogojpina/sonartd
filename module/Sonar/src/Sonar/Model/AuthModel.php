<?php

namespace Sonar\Model;


use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result as AuthResult;
use DoctrineORMModuleTest\Assets\GraphEntity\User;


class AuthModel {
	private $sm;
	private $config;
	
	public function __construct($sm, $config) {
		$this->sm = $sm;
		$this->config = $config;
	}	
	
	public function login($login, $pass) {
		$auth = new AuthenticationService();
		$authAdapter = new SonarAuthAdapter($this->sm, $this->config, $login, $pass);
		
		$result = $auth->authenticate($authAdapter);
		if (!$result->isValid()) {
			return false;
		}
		else {
			return $result->getIdentity();
		}		
	}
}

class SonarAuthAdapter implements AdapterInterface {
	private $sm;
	private $user;
	private $pass;
	private $config;
	
	public function __construct($sm, $config, $login, $pass) {
		$this->sm = $sm;
		$this->login = $login;
		$this->pass = $pass;
		$this->config = $config;
	}
	
	public function authenticate() {
		$urlAPI = $this->config['sonar']['urlAPI'];
		
		$url = "$urlAPI/api/authentication/validate";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->login:$this->pass");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$json = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if ($info['http_code'] == 200) {
			$output = json_decode($json);
			if ($output->valid == 1) {
				$userModel = new UserModel($this->sm);
				$user = $userModel->getByLogin($this->login);				
				return new AuthResult(AuthResult::SUCCESS, $user);
			}
		}		
		return new AuthResult(AuthResult::FAILURE_CREDENTIAL_INVALID, null);
	}
	
	
}

?>