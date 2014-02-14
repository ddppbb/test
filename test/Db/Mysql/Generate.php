<?php

namespace Db\Mysql;

use PDO;

class Generate extends \Db\Mysql\Db
{
	protected $connection = null;

	public $data = array();
	public $fields = array();
	public $ignore = false;
	public $table;

	public function __construct($host='', $database='', $user='', $pass='') {
		$this->connection = parent::getConnection($host, $database, $user, $pass);
	}

	public function generate($table, $amt) {
		$schema = new \Db\Mysql\Schema();
		$fields = $schema->getStructure($table);
		$this->table = $table;

		foreach ($fields as $field) {
			if ($field['Extra'] == 'auto_increment') {
				continue;
			}

			$this->fields[] = $field['Field'];

			if($field['Key'] == 'PRI' || $field['Key'] == 'UNI'){
				$this->ignore = true;
			}

			preg_match('/^(\w+)[\(]?([\d,]*)[\)]?( |$)/', $field['Type'], $matches);

			$type = $matches[1];
			$size = isset($matches[2]) ? $matches[2] : 0;

			$this->setRand($type, $size, $amt);
		}

		return $this->insert();
	}

	public function insert(){
		$sql = 'INSERT';
		$sql.= $this->ignore ? ' IGNORE INTO' : ' INTO';
		$sql.= ' `' . $this->table . '`';
		$sql.= ' (`' . implode('`, `', $this->fields) . '`)';
		$sql.= ' VALUES';

		$values = array();

		foreach ($this->data as $value) {
			$values[] = ' (\'' . implode('\', \'', $value) . '\')';
		}

		$sql.= implode(', ' , $values);

		return $this->connection->exec($sql);
	}

	public function setRand($type, $size, $amt){

		switch ($type) {
		    case 'int':
		        $this->data[$amt][] = mt_rand(0, 2147483647);
		        break;
		    case 'tinyint':
		        $this->data[$amt][] = mt_rand(0, 127);
		        break;
		    case 'smallint':
		        $this->data[$amt][] = mt_rand(0, 32767);
		        break;
		    case 'mediumint':
		        $this->data[$amt][] = mt_rand(0, 8388607);
		        break;
		    case 'bigint':
		        $this->data[$amt][] = mt_rand(0, 9223372036854775807);
		        break;
		    case 'float':
		    	$this->data[$amt][] = $this->randFloat(0, 3.402823466E+38);
		    	break;
		    case 'double':
		        $this->data[$amt][] = $this->randFloat(0, 1.7976931348623157E+308);
		        break;
		    case 'date':
		        $this->data[$amt][] = date('Y-m-d', mt_rand(-10800, 2114366400));
		        break;
		    case 'datetime':
		        $this->data[$amt][] = date('Y-m-d H:i:s', mt_rand(-10800, 2114366400));
		        break;
		    case 'timestamp':
		        $this->data[$amt][] = mt_rand(-10800, 2114366400);
		        break;
		    case 'time':
		        $this->data[$amt][]= date('H:i:s', mt_rand(-10800, 2114366400));
		        break;
		    case 'year':
		        $this->data[$amt][] = date('Y', mt_rand(-10800, 2114366400));
		        break;
		    case 'char':
		    	$this->data[$amt][] = substr(md5(rand()), 0, $size);
		    	break;
		    case 'varchar':
		        $this->data[$amt][] = substr(md5(rand()), 0, $size);
		        break;
		    case 'text':
		        $this->data[$amt][] = substr(md5(rand()), 0, 100);
		        break;
		}

		if(--$amt){
			$this->setRand($type, $size, $amt);
		}
	}

	public function randFloat($min, $max) {
		return ($min+lcg_value()*(abs($max-$min)));
	}
}