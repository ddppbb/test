<?php

namespace Db\Mysql;

use PDO;

class Schema extends \Db\Mysql\Db
{
	protected $connection = null;

	public function __construct($host='', $database='', $user='', $pass='') {
		$this->connection = parent::getConnection($host, $database, $user, $pass);
	}

	public function getTables() {
		return $this->connection->query('SHOW TABLES')
			->fetchAll(PDO::FETCH_COLUMN);
	}

	public function getStructure($table) {
		return $this->connection->query('SHOW FULL COLUMNS FROM ' . $table)
			->fetchAll(PDO::FETCH_ASSOC);
	}
}