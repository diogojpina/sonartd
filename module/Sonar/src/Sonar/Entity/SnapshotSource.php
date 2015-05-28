<?php

namespace Sonar\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity
 * @ORM\Table(name="snapshot_sources")*/
class SnapshotSource {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	protected $id;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Sonar\Entity\Snapshot", inversedBy="sources")
	 * @ORM\JoinColumn(name="snapshot_id", referencedColumnName="id")
	 **/
	private $snapshot;	
	
	/** @ORM\Column(type="string") */
	private $data;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getSnapshot() {
		return $this->snapshot;
	}
	
	public function setSnapshot($snapshot) {
		$this->snapshot = $snapshot;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function printData($line=1, $quantity=10) {
		$lines = explode("\n", $this->data);
		
		$top = ceil($quantity / 4);
		$ini = max($line - $top, 1);
		$max = min($line+$quantity-$top, count($lines));
		
		for ($i=$ini; $i < $max; $i++) {
			if ($i+1 == $line) echo '<b>';
			echo $i+1 . ': ' . $lines[$i] . "<br />";
			if ($i+1 == $line) echo '</b>';
		}
	}
}

?>