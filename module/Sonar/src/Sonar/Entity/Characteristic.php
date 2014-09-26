<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity 
  * @ORM\Table(name="characteristics")*/
class Characteristic {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}	
	
}

?>