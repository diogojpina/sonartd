<?php

namespace Sonar\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class RuleDao {
	protected $tableGateway;
	private $sm;
	private $dbAdapter;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Rule());
		$this->tableGateway = new TableGateway('rules', $this->dbAdapter, null, $resultSetPrototype);
	}
	
	public function get($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}	
}

?>