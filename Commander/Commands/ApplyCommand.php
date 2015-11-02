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
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to apply?'
            )
            ->addOption(
                'directive',
                null,
                InputOption::VALUE_REQUIRED,
                'Who do you want to applyxx?'
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

        $directive = $input->getOption('directive');

        if ($directive) {

            if(! $storage->fileExists($storage->path($directive))) {
                throw new InvalidArgumentException($storage->path($directive) . ".json file is not found");
            }
            $encoded = @file_get_contents($storage->path($directive));

            if(! Json::valid($encoded)) {
                throw new InvalidArgumentException($storage->path($directive) . ".json file is not valid");
            }

            $directives = Json::decode($encoded);

            if(!is_array(reset($directives))) {
                $directives = [$directives];
            }

            foreach($directives as $directive) {

                $refDirective = new ReflectionClass('\\Commander\\Generates\\Generate' . ucfirst($directive['type']));

                echo $directive['description'] . ' running...'."\n";
                call_user_func_array(array($refDirective->newInstance($input, $output), "run"), [$directive, $input]);
            }
           
        } else {
            $text = 'Hello';
        }

        /*
        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }*/

       // $output->writeln('hello');
    }

}