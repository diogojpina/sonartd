<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="group_roles")*/
class GroupRoles {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Group", inversedBy="roles")
	 * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
	 **/
	private $group;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Project", inversedBy="groupRoles")
	 * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
	 **/
	private $project;
	
	/** @ORM\Column(type="string") */
	protected $role;
}

?>