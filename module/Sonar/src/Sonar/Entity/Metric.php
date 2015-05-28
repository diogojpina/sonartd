<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="metrics")*/
class Metric {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string") */
	protected $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\ProjectMeasure", mappedBy="metric")
	 **/
	private $measures;	
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}	
	
	public function getMeasures() {
		return $this->measures;
	}
	
	public function setMeasures($measures) {
		$this->measures = $measures;
	}
	
}

?>