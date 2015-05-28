<?php

namespace Sonar\Model;

use Sonar\Entity\TechnicalDebt;
use Sonar\Entity\TechnicalDebtMeasure;

class TechnicalDebtMeasureModel {
	private $sm;
	private $repository;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->repository = $sm->getRepository('Sonar\Entity\TechnicalDebtMeasure');
	}

	public function save(TechnicalDebtMeasure $technicalDebtMeasure) {
		if (!$technicalDebtMeasure->getId()) {
			$this->sm->persist($technicalDebtMeasure);
		}
		$this->sm->flush();
	}	
	
	public function deleteByTechnicalDebt($technicalDebt) {
		$strQuery = 'delete FROM Sonar\Entity\TechnicalDebtMeasure tdm where tdm.technicalDebt = :technicalDebt';
		$query = $this->sm->createQuery($strQuery);
		$query->setParameter('technicalDebt', $technicalDebt);
		$query->execute();		
	}
}

?>