<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="technical_debt_measures")*/
class TechnicalDebtMeasure {	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Metric", inversedBy="measures")
	 * @ORM\JoinColumn(name="metric_id", referencedColumnName="id")
	 **/
	private $metric;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\TechnicalDebt", inversedBy="measures")
	 * @ORM\JoinColumn(name="technical_debt_id", referencedColumnName="id")
	 **/
	private $technicalDebt;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Rule")
	 * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
	 */
	private $rule;
	
	/** @ORM\Column(type="decimal") */
	private $value;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getMetric() {
		return $this->metric;
	}
	
	public function setMetric($metric) {
		$this->metric = $metric;
	}
	
	public function getTechnicalDebt() {
		return $this->technicalDebt;
	}
	
	public function setTechnicalDebt($technicalDebt) {
		$this->technicalDebt = $technicalDebt;
	}
	
	public function getRule() {
		return $this->rule;
	}	
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
}

?>