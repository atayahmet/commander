<?php namespace Commander\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use Commander\Helpers\Storage;
use Commander\Helpers\Json;
use InvalidArgumentException;
use ReflectionException;
use ReflectionClass;

class ApplyCommand extends Command {

	protected function configure()
    {
        $this
            ->setName('apply')
            ->setDescription('Proccess directive of defined')
            ->addArgument(
                'directive',
                InputArgument::REQUIRED,
                'Who do you want to apply?'
            )
            ->addOption(
               'yell',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            );
    }

    /**
     * @param  object
     * @param  object
     * @return void
     */
    protected function execute(ArgvInput $input, ConsoleOutput $output)
    {
        $storage = new Storage;
        
        if(! $storage->isEnabled()) {
            continue;
        }

        $directive = $input->getArgument('directive');

        if ($directive) {

            if(! $storage->fileExists($storage->path($directive))) {
                throw new InvalidArgumentException($storage->path($directive) . ".json file is not found");
            }
            $encoded = @file_get_contents($storage->path($directive) . '.json');

            if(! Json::valid($encoded)) {
                throw new InvalidArgumentException($storage->path($directive) . ".json file is not valid");
            }

            $directives = Json::decode($encoded);

            try {

                if(!is_array(reset($directives))) {
                    $directives = [$directives];
                }

                foreach($directives as $directive) {
                    $refDirective = new ReflectionClass('\\Commander\\Generates\\Generate' . ucfirst($directive['type']));

                    echo $directive['name'] . ' running...';
                    call_user_func_array(array($refDirective->newInstance(), "run"), $directive);
                }

            }
            catch(ReflectionException $e) {
                throw new InvalidArgumentException("Undefined type: " . $directive[0]['type']);
                
            }

        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        //$output->writeln($text);
    }

}