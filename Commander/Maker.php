<?php namespace Commander;

use Symfony\Component\Console\Application;
use Commander\Terminal\Colorant;
use Commander\Terminal\ArgvParser;
use Exception;
use ReflectionClass;

class Maker {
	
	protected $command;
	protected $storage;

	public function __construct()
	{
		
	}

	public function run(array $_argv)
	{
		try {

			if(sizeof($_argv) < 2) {
				$this->commandList();
				return;
			}

			$this->setParameter($_argv);
			
			$commandClass = 'Commander\\Commands\\' . $this->command . 'Command';

			if(! class_exists($commandClass)) {
				throw new Exception('command not found');
			}

			$refCommand = new ReflectionClass($commandClass);
			$commandInstance = $refCommand->newInstance($this->storage);

			$app = new Application();
			$app->add($commandInstance);
			$app->run();

		}
		catch(Exception $e) {

			exit(chr(27) . "[44m".$e.chr(27) . "[0m" . "\n");
		}
	}

	/**
	 * Set parameters
	 * 
	 * @param array
	 */
	public function setParameter(array $_argv)
	{
		$validParameter = true;

		if(! isset($_argv[1])) {
			$validParameter = false;
		}

		if(! $validParameter) {
			throw new Exception();
		}

		$this->command = ucfirst($_argv[1]);
	}

	public function CommandList()
	{
		exit("\033[" . '0;32' . "m"."---- Command List ----"."\033[0m"."\n\n");
	}
}