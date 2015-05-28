<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ORM\Entity
 * @ORM\Table(name="users")*/
class User {
	public function __construct() {
		$this->groups = new ArrayCollection();
	}
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/** @ORM\Column(length=40) */
	protected $login;
	
	/** @ORM\Column(type="string") */
	protected $name;
	
	/** @ORM\Column(type="string") */
	protected $email;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Sonar\Entity\Group", inversedBy="users")
	 * @ORM\JoinTable(name="groups_users",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 *      )
	 **/
	private $groups;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function getLogin() {
		return $this->login;
	}
	
	public function setLogin($login) {
		$this->login = $login;
	}	
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}	
	
	public function getGroups() {
		return $this->groups;
	}
}

?>