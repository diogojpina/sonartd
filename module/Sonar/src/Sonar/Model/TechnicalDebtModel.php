<?php

namespace Sonar\Model;

use Sonar\Entity\TechnicalDebt;

class TechnicalDebtModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\TechnicalDebt');
	}
	
	public function save(TechnicalDebt $technicalDebt) {
		if (!$technicalDebt->getId()) {
			$this->sm->persist($technicalDebt);
		}
		$this->sm->flush();
	}
}

?>