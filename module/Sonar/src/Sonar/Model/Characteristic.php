<?php

namespace Sonar\Model;

class Characteristic {
	
	public $id;
	
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
		$attrs = array('id');
		foreach ($attrs as $attr) {
			$data[$attr] = $this->$attr;
		}
		return $data;
	}	
}

?>