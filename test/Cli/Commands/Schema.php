<?php

namespace Cli\Commands;

class Schema extends \Cli\Commands\Commands
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
		echo 'Show structure table:';

		$input = trim(fgets(STDIN));

		$schema = new \Db\Mysql\Schema(
			$this->options['host'],
			$this->options['database'],
			$this->options['user'],
			$this->options['password']
		);

		if (!empty($input)) {

			$this->showTable("|%10.10s |%10.10s |%10.10s |%4.4s |%5.5s |%8.8s |%5.5s |%35.35s |%20.20s |\n", 
				array('Field', 'Type', 'Collation', 'Null', 'Key', 'Default', 'Extra', 'Privileges', 'Comment'), 
				$schema->getStructure($input));
			
		} else {
			$this->showTable("|%15.15s |\n", array('Name'), $schema->getTables());
		}

		return $this->prompt();
	}

    public function help() {
    	echo "\nUsage: ";
    	echo "\n structure --user=[user] --host=[host] --password=[password] --database=[database]";
    	echo "\n\n";
    }
}