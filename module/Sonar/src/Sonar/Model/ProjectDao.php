<?php
namespace Sonar\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class ProjectDao {
	protected $tableGateway;
	private $sm;
	private $dbAdapter;
	
	public function __construct($sm) {
		$this->sm = $sm;
		$this->dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Project());
		$this->tableGateway = new TableGateway('projects', $this->dbAdapter, null, $resultSetPrototype);
	}

	public function getRoots() {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select(array('p' => 'projects'));
		$select->where(array('p.scope' => 'PRJ', 'p.qualifier' => 'TRK', 'p.enabled' => 1));
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		$projects = array();
		foreach ($results as $result) {
			$project = new Project();
			$project->exchangeArray($result);
				
			$projects[] = $project;
		}
		
		return $projects;		
	}
	
	public function getSourceFiles(Project $project) {
		$sql = new Sql($this->dbAdapter);
		$select = $sql->select(array('p' => 'projects'));
		$select->where(array('p.root_id' => $project->id, 'p.scope' => 'FIL', 'p.qualifier' => 'FIL', 'p.enabled' => 1));
	
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		$projects = array();
		foreach ($results as $result) {
			$project = new Project();
			$project->exchangeArray($result);
	
			$projects[] = $project;
		}
	
		return $projects;
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
