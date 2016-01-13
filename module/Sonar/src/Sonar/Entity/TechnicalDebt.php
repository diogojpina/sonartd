<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\PublicKey\Rsa\PublicKey;
use Sonar\Model\TechnicalDebtHelper;

/** @ORM\Entity
 * @ORM\Table(name="technical_debts")*/
class TechnicalDebt {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
    /**
     * @ORM\OneToOne(targetEntity="Sonar\Entity\Issue", inversedBy="technicalDebt")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     **/
	protected $issue;
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\TechnicalDebtMeasure", mappedBy="technicalDebt", cascade={"all"})
	 **/
	private $measures;	
	
	/** @ORM\Column(type="integer") */
	protected $sonarTD;
	
	/** @ORM\Column(type="integer") */
	protected $modelTD;
	
	/** @ORM\Column(type="integer") */
	protected $regressionTD;
	
	/** @ORM\Column(type="integer") */
	protected $realTD;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getIssue() {
		return $this->issue;
	}
	
	public function setIssue($issue) {
		$this->issue = $issue;
	}
	
	public function getMeasures() {
		if ($this->measures == null)
			return array();
		return $this->measures;
	}
	
	public function setMeasures($measures) {
		$this->measures = $measures;
	}
		
	public function getSonarTD() {
		return $this->sonarTD;
	}
	
	public function setSonarTD($sonarTD) {
		$this->sonarTD = $sonarTD;
	}
	
	public function getModelTD() {
		return $this->modelTD;
	}
	
	public function setModelTD($modelTD) {
		$this->modelTD = $modelTD;
	}
	
	public function getRegressionTD() {
		return $this->regressionTD;
	}
	
	public function setRegressionTD($regressionTD) {
		$this->regressionTD = $regressionTD;
	}	
	
	public function getRealTD() {
		return $this->realTD;
	}
	
	public function setRealTD($realTD) {
		$this->realTD = $realTD;
	}
	
	public function getTechnicalDebt() {
		if ($this->modelTD > 0) {
			return $this->modelTD;
		}
		else if ($this->regressionTD > 0) {
			return $this->regressionTD;
		}
		else {
			return $this->sonarTD;
		}
	}
	
	public function getTechnicalDebtFormated($workHours=8) {
		$tdHelper = new TechnicalDebtHelper();
		return $tdHelper->format($this->getTechnicalDebt(), $workHours);
	}
	
	public function getMetrics() {
		$metrics = array();
		foreach ($this->measures as $metric) {
			$name = $metric->getMetric()->getName();
			$metrics[$name] = $metric;
		}
		return $metrics;
	}
	

}

?>