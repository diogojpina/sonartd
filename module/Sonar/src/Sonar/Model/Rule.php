<?php

namespace Sonar\Model;

class Rule {
	public $id;
	public $plugin_rule_key;
	
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
		//todo
		$data = array();
		$attrs = array('id');
		foreach ($attrs as $attr) {
			$data[$attr] = $this->$attr;
		}
		return $data;
	}	
}

?>