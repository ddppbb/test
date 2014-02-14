<?php

namespace Cli\Commands;

class Generate extends \Cli\Commands\Commands
{
	protected $options = array();

	public function __construct($options){
		$this->options = $options;

		if (isset($this->options['user'])
			&& isset($this->options['host'])
			&& isset($this->options['password'])
			&& isset($this->options['database'])) {
			
			$this->prompt();

		} else {
			$this->help();
		}
	}

	public function prompt() {
		echo 'Table:';
		$table = trim(fgets(STDIN));
		echo 'Row count (1 - 100):';
		$amt = trim(fgets(STDIN));

		if ($amt<1 || $amt>100) {
			die("Sorry!\n");
		}

		$generate = new \Db\Mysql\Generate(
			$this->options['host'],
			$this->options['database'],
			$this->options['user'],
			$this->options['password']
		);

		echo 'Affected rows: ' . $generate->generate($table, $amt);
		echo "\n\n";

		return $this->prompt();
	}

    public function help() {
    	echo "\nUsage: ";
    	echo "\n generate --user=[user] --host=[host] --password=[password] --database=[database]";
    	echo "\n\n";
    }
}