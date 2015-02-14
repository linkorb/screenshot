<?php

namespace Screenshot\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Screenshot\Shooter;
use Screenshot\Loader\JsonScriptLoader;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\Selenium2Driver;
use Selenium\Client as SeleniumClient;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('screenshot:run')
            ->setDescription('Run screenshot script')
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename of the script')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename  = $input->getArgument('filename');
        $output->write("Loading $filename\n");
        $driver = new Selenium2Driver('chrome');
        $session = new Session($driver);

        
        $shooter = new Shooter($session);
        $loader = new JsonScriptLoader();
        $script = $loader->load($filename, $shooter);

        $shooter->runScript($script);
    }
}
