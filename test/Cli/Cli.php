<?php

namespace Cli;

class Cli
{
    const COMMANDS = '\Cli\Commands\\';

    protected $exec = null;
    protected $command = null;
    protected $options = array();

	public function __construct($args = null){
        $this->setArguments($args);

        if (class_exists(self::COMMANDS . ucfirst($this->command))) {
            $command = self::COMMANDS . ucfirst($this->command);
            new $command($this->options);
        } else {
            $this->help();
        }
    }

    protected function setArguments($args) {
        $this->exec = array_shift($args);

        while (($arg = array_shift($args)) != null) {
            if ( substr($arg, 0, 2) === '--' ) {
                $option = substr($arg, 2);

                if (strpos($option,'=') !== false){
                    $option = explode('=', $option, 2);
                    $this->options[$option[0]] = $option[1];
                } else {
                    $this->options[$option] = true;
                }
                continue;
            }

            $this->command = $arg;
            continue;
        }

        return $this;

    }

    public function help(){
    	echo "\nThe following commands are available:";
    	echo "\n - schema";
    	echo "\n - generate";
        echo "\n";
    	echo "\nTo see individual command help, use the following:";
    	echo "\n [command-name] --help";
    	echo "\n\n";
    }
}