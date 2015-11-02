<?php namespace Commander\Generates;

use Symfony\Component\Console\Input\ArgvInput;
use Commander\Generates\Generate;

class GenerateCommand extends Generate {

	public function __construct(ArgvInput $input)
	{

	}

	public function run(array $directives)
	{
		//exit(var_dump(func_get_args()));
		echo shell_exec('php composer');
	}

}