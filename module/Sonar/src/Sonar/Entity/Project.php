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
    
    /** @ORM\Column(type="string", name="long_name") */
    protected $longName;
    
    /** @ORM\Column(type="string") */
    protected $kee;
    
    /** @ORM\Column(length=3) */
    protected $scope;
    
    /** @ORM\Column(length=3) */
    protected $qualifier;
    
    /** @ORM\Column(type="integer") */
    protected $enabled;
    
    /** @ORM\Column(type="string") */
    protected $uuid;
    
    /**
     * @ORM\OneToMany(targetEntity="Sonar\Entity\Issue", mappedBy="project")
     **/
    private $issues;
    
    /**
     * @ORM\OneToMany(targetEntity="Sonar\Entity\Snapshot", mappedBy="project")
     **/
    private $snapshots;    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Sonar\Entity\GroupRoles", mappedBy="project")
     **/
    private $groupRoles;
    
    /**
     * @ORM\OneToMany(targetEntity="Sonar\Entity\UserRoles", mappedBy="project")
     **/
    private $userRoles;    
    
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
	
	public function getLongName() {
		return $this->longName;
	}
	
	public function setLongName($longName) {
		$this->longName = $longName;
	}
	
	public function getDirName() {
		$len = strlen($this->longName) - strlen($this->name) - 1;
		return substr($this->longName, 0, $len);
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
	
	public function getSnapshots() {
		if ($this->snapshots == null)
			return array();
		return $this->snapshots;
	}
	
	public function setSnapshots($snapshots) {
		$this->snapshots = $snapshots;
	} 
	
	public function getSnapshot() {
		foreach ($this->snapshots as $snapshot) {
			if ($snapshot->isLast() == 1)
				return $snapshot;
		}
		return null;				
	}
	
	public function getUUId() {
		return $this->uuid;
	}
	
	public function getKee() {
		return $this->kee;
	}
}

?>