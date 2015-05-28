<?php

namespace Sonar\Model;

use Sonar\Entity\Characteristic;

class CharacteristicModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\Characteristic');
	}
	
	public function get($id) {
		$id = (int) $id;
		return $this->sm->find('Sonar\Entity\Characteristic', $id);
	}
	
	public function getRoots() {
		return $this->repository->findBy(array('parent' => null));
	}
	
	public function getSubCharacteristics(Characteristic $characteristic) {
		
	}
}

?>