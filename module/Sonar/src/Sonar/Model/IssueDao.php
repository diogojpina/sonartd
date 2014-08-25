<?php

namespace Sonar\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class IssueDao {
	
	protected $tableGateway;
	private $sm;
	private $dbAdapter;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Issue());
		$this->tableGateway = new TableGateway('issues', $this->dbAdapter, null, $resultSetPrototype);
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

	public function getIssuebyProject(Project $project) {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select(array('i' => 'issues'));
		$select->where(array('i.component_id' => $project->id, 'i.status' => 'OPEN'));
		$select->order('i.line');
	
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		$ruleDao = new RuleDao($this->sm);
		$issues = array();
		foreach ($results as $data) {
			$issue = new Issue();
			$issue->exchangeArray($data);
			
			$issue->rule = $ruleDao->get($issue->rule_id);
			$issues[] = $issue;
		}
		return $issues;
	}	
}

?>