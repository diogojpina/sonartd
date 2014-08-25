<?php
namespace Sonar\Model;

class Project {
	public $id;
	public $name;
	public $description;


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
