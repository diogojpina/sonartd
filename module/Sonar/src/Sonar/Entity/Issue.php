<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity 
  * @ORM\Table(name="issues")*/
class Issue {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Project", inversedBy="issues")
	 * @ORM\JoinColumn(name="component_id", referencedColumnName="id")
	 **/
	private $project;	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Rule", inversedBy="issues")
	 * @ORM\JoinColumn(name="rule_id", referencedColumnName="id")
	 **/
	protected $rule;
	
	/** @ORM\Column(length=3) */
	protected $severity;
	
	/** @ORM\Column(type="integer") */
	protected $line;
	
	/** @ORM\Column(length=11) */
	protected $status;
	
	/** @ORM\Column(type="string") */
	protected $message;
	
	/** @ORM\Column(length=20) */
	protected  $effort_to_fix;
	
	/** @ORM\Column(type="integer", name="technical_debt") */
	protected  $technicalDebt;
		
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getProject() {
		return $this->project;
	}
	
	public function setProject(Project $Project) {
		$this->project = $project;
	}	
	
	public function getRule() {
		return $this->rule;
	}	
	
	public function setRule(Rule $rule) {
		$this->rule = $rule;
	}	
	
	public function getSeverity() {
		return $this->severity;
	}
	
	public function setSeverity($severity) {
		$this->severity = $severity;
	}
	
	public function getLine() {
		return $this->line;
	}
	
	public function setLine($line) {
		$this->line = $line;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}				
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}	
	
	public function getTechnicalDebt() {
		return $this->technicalDebt;
	}
	
	public function setTechinicalDebt($technicalDebt) {
		$this->technicalDebt = $technicalDebt;
	}
	
}

?>