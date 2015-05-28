<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity 
  * @ORM\Table(name="characteristics")*/
class Characteristic {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string") */
	protected $name;

	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\Characteristic", mappedBy="parent")
	 **/
	protected $subcharacteristics;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Characteristic", inversedBy="subcharacteristics")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
	 **/
	protected $parent;	

	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\Rule", mappedBy="characteristic")
	 **/
	protected $rules;

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
	
	public function getSubCharacteristics() {
		return $this->subcharacteristics;
	}
	
	public function getRules() {
		return $this->rules;
	}
	
}

?>