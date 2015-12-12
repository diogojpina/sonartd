<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="groups")*/
class Group {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string") */
	protected $name;
	
	/** @ORM\Column(type="string") */
	protected $description;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Sonar\Entity\User", mappedBy="groups")
	 **/
	private $users;
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\GroupRoles", mappedBy="group")
	 **/
	private $roles;
	
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

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}	
	
	public function getUsers() {
		return $this->users;
	}
	
}

?>