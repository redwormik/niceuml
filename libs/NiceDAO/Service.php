<?php

namespace NiceDAO;

use Nette\Database\Connection;


class Service extends \Nette\Object {

	/** @var string */
	protected $tableName;

	/** @var Connection */
	protected $connection;

	/** @var EntityFactory */
	protected $entityFactory;

	/** @var NewEntityTable */
	protected $newEntityTable;


	public function __construct($tableName, Connection $connection, EntityFactory $entityFactory) {
		$this->tableName = $tableName;
		$this->connection = $connection;
		$this->entityFactory = $entityFactory;
	}


	public function table() {
		return new Selection($this->tableName, $this->connection, $this->entityFactory);
	}


	protected function getNewEntityTable() {
		if ($this->newEntityTable === NULL)
			$this->newEntityTable = new NewEntityTable($this->tableName, $this->connection, $this->entityFactory);
		return $this->newEntityTable;
	}


	public function create($data = NULL) {
		$entity = $this->getNewEntityTable()->create();
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function get($id) {
		return $this->table()->get($id);
	}


	public function save(Entity $entity = NULL, $data = NULL) {
		if ($entity === NULL)
			$entity = $this->create();

		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}

		if ($this->isNew($entity))
			return $this->table()->insert($entity);
		
		$entity->update();
		return $entity;
	}


	public function delete(Entity $entity) {
		return $entity->delete();
	}


	protected function isNew(Entity $entity) {
		return $entity->getPrimary(FALSE) === NULL;
	}

}
