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
	
	/** @ORM\Column(name="manual_severity", type="integer") */
	protected $manualSeverity;
	
	/** @ORM\Column(type="integer") */
	protected $line;
	
	/** @ORM\Column(length=20) */
	protected $status;
	
	/** @ORM\Column(length=20) */
	protected $resolution;
	
	/** @ORM\Column(type="string") */
	protected $message;
	
	/** @ORM\Column(type="string") */
	protected $assignee;
	
	/** @ORM\Column(length=20) */
	protected  $effort_to_fix;
	
	/** @ORM\OneToOne(targetEntity="Sonar\Entity\TechnicalDebt", mappedBy="issue") */
	protected $technicalDebt;
	
	/** @ORM\Column(type="integer", name="technical_debt") */
	protected $td;
	
	/** @ORM\Column(type="string") */
	protected $component_uuid;
		
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getProject() {
		return $this->project;
	}
	
	public function setProject(Project $project) {
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
	
	public function getSeverityTag() {
		switch ($this->severity) {			
			case 'CRITICAL':
				return 'danger';
			case 'MAJOR':
				return 'warning';				
			case 'MINOR':
				return 'primary';
				break;
			case 'INFO':
				return 'info';
			default:
				return 'default';
		}
	}
	
	public function getManualSeverity() {
		return $this->manualSeverity;
	}
	
	public function setManualSeverity($manualSeverity) {
		$this->manualSeverity = $manualSeverity;
	}
	
	public function getResolution() {
		return $this->resolution;
	}
	
	public function setResolution($resolution) {
		$this->resolution = $resolution;
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
	
	public function getAssignee() {
		return $this->assignee;
	}
	
	public function setAssignee($assignee) {
		$this->assignee = $assignee;
	}	
	
	public function getTechnicalDebt() {
		return $this->technicalDebt;
	}
	
	public function setTechinicalDebt($technicalDebt) {
		$this->technicalDebt = $technicalDebt;
	}
	
	public function getTD() {
		return $this->td;
	}
	
	public function setTD($td) {
		$this->td = $td;
	}
	
	public function getComponentUUID() {
		return $this->component_uuid;
	}
}

?>