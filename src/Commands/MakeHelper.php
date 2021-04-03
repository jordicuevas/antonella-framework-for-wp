<?php

namespace CH\Commands;
 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
 
/**
  * @see https://code.tutsplus.com/es/tutorials/how-to-create-custom-cli-commands-using-the-symfony-console-component--cms-31274
  *		 https://symfony.com/doc/current/console
  *		 https://symfony.com/doc/current/console/input.html
  *		 https://symfony.com/doc/current/console/input.html#using-command-options		
  */
 class MakeHelper extends Command
{
    protected $namespace;

    protected $paths;
    
    protected function configure()
    {
        $this->setName('make:helper')
            ->setDescription('Make a helper file in the folder src/Helpers')
            ->setHelp('Set a name for you helper. For example auxiliarHelper')
            ->addArgument('nameHelper', InputArgument::REQUIRED, 'Name to helper file');
       
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $name = $input->getArgument('nameHelper');
        $this->makeHelper($name);
		$output->writeln("<info>The Helper $name.php created into src/Helper folder</info>");
        
	}
    /**
     * Crea un fichero helpers para albergar funciones auxiliares.
     *
     * @param array $data argumentos de la linea de comandos
     *                    donde $data[2] representa el nombre del fichero
     *                    Uso:	php antonella helper auxiliares
     *                    Out: src/Helpers/auxiliares.php
     */
    public function makeHelper($data)
    {
        $c = new \Console;
        $this->namespace = $c->namespace;
        $this->paths = $c->paths;

        $target = $c->getPath('helpers', $data);
        if (!file_exists(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }
        $StubGenerator = $this->namespace.'\Classes\StubGenerator';
        $stub = new $StubGenerator(
            $c->getPath('stubs', 'helper'),
            $target
        );

        $folder = array_reverse(explode('/', dirname($target)))[0];
        $stub->render([
            '%NAME%' => $data,
        ]);
    }
    
}