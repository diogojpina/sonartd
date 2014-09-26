<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity 
  * @ORM\Table(name="projects")*/
class Project {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sonar\Entity\Project")
     * @ORM\JoinColumn(name="root_id", referencedColumnName="id")
     **/
    private $root;    

    /** @ORM\Column(type="string") */
    protected $name;
    
    /** @ORM\Column(length=3) */
    protected $scope;
    
    /** @ORM\Column(length=3) */
    protected $qualifier;
    
    /** @ORM\Column(type="integer") */
    protected $enabled;
    
    /**
     * @ORM\OneToMany(targetEntity="Sonar\Entity\Issue", mappedBy="project")
     **/
    private $issues;

	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getRoot() {
		return $this->root;
	}
	
	public function setRoot($root) {
		$this->root = $root;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}	
	
	public function getScope() {
		return $this->scope;
	}
	
	public function setScope($scope) {
		$this->scope = $scope;
	}

	public function getQualifier() {
		return $this->qualifier;
	}
	
	public function setQualifier($qualifier) {
		$this->qualifier = $qualifier;
	}	
	
	public function getEnabled() {
		return $this->enabled;
	}
	
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}
	
	public function getIssues() {
		return $this->issues;
	}
	
	public function setIssues($issues) {
		$this->issues = $issues;
	}	
}

?>