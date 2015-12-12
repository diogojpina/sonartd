<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="user_roles")*/
class UserRoles {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;	

	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\User", inversedBy="roles")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 **/
	private $user;
	
    /**
     * @ORM\ManyToOne(targetEntity="Sonar\Entity\Project", inversedBy="userRoles")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     **/
	private $project;

}

?>