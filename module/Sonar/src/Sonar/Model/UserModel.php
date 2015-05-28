<?php

namespace Sonar\Model;

use Sonar\Entity\User;

class UserModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;	
		$this->repository = $sm->getRepository('Sonar\Entity\User');
	}
	
	public function findAll() {
		return $this->repository->findAll();
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\User', $id);
	}
	
	public function getByLogin($login) {
		return $this->repository->findOneBy(array('login' => $login));;
	}
	

}

?>