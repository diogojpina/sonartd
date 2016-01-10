<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="project_measures")*/
class ProjectMeasure {
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
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Snapshot", inversedBy="measures")
	 * @ORM\JoinColumn(name="snapshot_id", referencedColumnName="id")
	 **/
	private $snapshot;

	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Rule")
	 * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
	 */
	private $rule;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Characteristic")
	 * @ORM\JoinColumn(name="characteristic_id", referencedColumnName="id")
	 */
	private $characteristic;
	
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
	
	public function getSnapshot() {
		return $this->snapshot;
	}
	
	public function setSnapshot($snapshot) {
		$this->snapshot = $snapshot;
	}	
	
	public function getRule() {
		return $this->rule;
	}
	
	public function getCharacteristic() {
		return $this->characteristic;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
}

?>