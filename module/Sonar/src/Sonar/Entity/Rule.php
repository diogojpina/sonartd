<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity 
  * @ORM\Table(name="rules")*/
class Rule {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	/** @ORM\Column(type="string", name="plugin_rule_key") */
	protected $pluginRuleKey;
	
	
	/**
	 * @ORM\OneToMany(targetEntity="Sonar\Entity\Issue", mappedBy="rule")
	 **/
	private $issues;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getPluginRuleKey() {
		return $this->pluginRuleKey;
	}
	
	public function setPluginRuleKey($pluginRuleKey) {
		$this->pluginRuleKey = $pluginRuleKey;
	}

	public function getIssues() {
		return $this->issues;
	}
	
	public function setIssues($issues) {
		$this->issues = $issues;
	}	
	
	
	
	
}

?>