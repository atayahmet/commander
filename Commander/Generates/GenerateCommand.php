<?php namespace Commander\Generates;

use Commander\Generates\Generate;

class GenerateCommand extends Generate {

	public function run()
	{
		//exit(var_dump(func_get_args()));
		echo shell_exec('php commander');
	}

}