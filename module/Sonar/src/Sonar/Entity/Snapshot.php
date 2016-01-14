<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="snapshots")*/
class Snapshot {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Project", inversedBy="snapshots")
	 * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
	 **/
	private $project;	
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\SnapshotSource", mappedBy="snapshot")
	 **/
	private $sources;
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\ProjectMeasure", mappedBy="snapshot")
	 **/
	private $measures;
	
	/** @ORM\Column(type="integer") */
	protected $islast;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getProject() {
		return $this->project;
	}
	
	public function setProject($project) {
		$this->project = $project;
	}
	
	public function getSources() {
		return $this->sources;
	}
	
	public function setSources($sources) {
		$this->sources = $sources;
	}
	
	public function getSource() {
		if (count($this->sources) > 0) {
			foreach ($this->sources as $source) {

			}
			return $source;
		} 
		else {
			return null;
		}
	}
	
	public function getMeasures() {
		return $this->measures;
	}
	
	public function setMeasures($measures) {
		$this->measures = $measures;
	}
	
	public function isLast() {
		return $this->islast;
	}
	
}

?>