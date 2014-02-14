<?php

namespace Db\Mysql;

use PDO;
use PDOException;

class Db
{
	protected static $dbh = null;

	private function __construct(){}

	private function __clone(){}

	public static function getConnection($host='', $database='', $user='', $pass='') {
		if (null === self::$dbh) {
			$dns = 'mysql:dbname=' . $database . ';host=' . $host;

			try { 
        		self::$dbh = new PDO($dns, $user, $pass);
        	}
        	catch (PDOException $e) {
            	die($e->getMessage());
        	}
        }
        
        return self::$dbh;
	}
}