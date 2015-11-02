<?php namespace Commander\Generates;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Commander\Generates\Generate;
use Commander\Helpers\Prompter;
use Commander\Exceptions\MissingArgumentException;

class GenerateClass extends Generate {

	protected $ext = '.php';
	protected $skeleton;
	protected $prompter;
	protected $input;
	protected $output;
	protected $directives;

	public function __construct(ArgvInput $input, ConsoleOutput $output)
	{
		$this->prompter = new Prompter;
		$this->input = $input;
		$this->output = $output;
		$this->skeleton = file_get_contents(__DIR__.'/templates/class.tpl.php');
	}

	public function run(array $directives)
	{
		

		$this->setFilename($directives);
		
		$filePath = $directives['path'].$this->filename.$this->ext;
		
		if(file_exists($filePath)) {
			$promptResult = $this->prompter->run(['yes', 'no'], 'xxxx');

			exit(var_dump($promptResult));
		}
		touch($filePath);
		file_put_contents($filePath, $this->skeleton);
		$this->output->writeln('<fg=green>' . $this->filename . ' class created.</>');
	}

	public function setFilename(array $directives)
	{
		if(! isset($directives['name']) && ! $this->input->getArgument('name')) {
			throw new MissingArgumentException("Class name not defined");
		}

		if($this->input->getArgument('name')) {
			$this->filename = $this->input->getArgument('name');
		}else{
			$this->filename = $directives['name'];
		}
	}
}