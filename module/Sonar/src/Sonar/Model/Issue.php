<?php

namespace Sonar\Model;

class Issue {
	
	public $id;
	public $rule_id;
	public $severity;
	public $line;
	public $status;
	public $message;
	public $effort_to_fix;
	public $technical_debt;
	
	public $rule;
	
	public function exchangeArray($data) {
		if ($data) {
			foreach ($data as $attr => $val) {
				if (property_exists(get_class($this), $attr))  {
					$this->$attr = $val;
				}
			}
		}
	}
	
	public function getArrayDb() {
		//change
		$data = array();
		$attrs = array('id', 'rule_id', 'severity');
		foreach ($attrs as $attr) {
			$data[$attr] = $this->$attr;
		}
		return $data;
	}	
	
}

?>