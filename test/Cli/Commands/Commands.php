<?php

namespace Cli\Commands;

abstract class Commands 
{
	abstract public function help();

	public function showTable($format, $titles, $data){
		call_user_func_array('printf', array_merge((array)$format, $titles));

		foreach ($data as $key => $value) {
			call_user_func_array('printf', array_merge((array)$format, (array)$value));				
		}
    }
}