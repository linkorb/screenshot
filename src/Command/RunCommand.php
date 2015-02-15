<?php

namespace Screenshot\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Screenshot\Shooter;
use Screenshot\Loader\JsonScriptLoader;
use Screenshot\Loader\XmlScriptLoader;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\Driver\Selenium2Driver;
use Selenium\Client as SeleniumClient;
use Aws\S3\S3Client;
use RuntimeException;


use ObjectStorage\Service as ObjectStorageService;


class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('screenshot:run')
            ->setDescription('Run screenshot script')
            ->addArgument('script', InputArgument::REQUIRED, 'The filename of the script file')
            ->addArgument('config', InputArgument::REQUIRED, 'The filename of the config file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scriptfilename = $input->getArgument('script');
        $configfilename = $input->getArgument('config');
        $output->write("Loading $scriptfilename with config $configfilename\n");
        
        
        $configjson = file_get_contents($configfilename);
        $config = json_decode($configjson, true);
        //print_r($config);
        if (count($config)==0) {
            throw new RuntimeException("Could not parse configfile (invalid json?)");
        }
        
        // Setup Selenium session
        $driver = new Selenium2Driver('chrome');
        $session = new Session($driver);

        // Setup storage
        
        switch ($config['storage']) {
            case "file":
                $path = $config['output-path'];
                if (!$path || !is_dir(realpath($path))) {
                    throw new RuntimeException("output-path not configured properly");
                }
                //echo realpath($path) . "\n";
                $storageadapter = new \ObjectStorage\Adapter\FileAdapter(realpath($path) . '/');
                break;
            case "s3":
                $key = $config['aws-access-key'];
                $secret = $config['aws-secret'];
                $bucketname = $config['aws-bucket'];
                $client = S3Client::factory(array(
                    'key' => $key,
                    'secret' => $secret
                ));

                $storageadapter = new \ObjectStorage\Adapter\S3Adapter($client, $bucketname);
                break;
            default:
                throw new RuntimeException("No supported storage type defined in configfile");
        }

        $storageservice = new ObjectStorageService($storageadapter);
        
        // Instantiate the Shooter
        $shooter = new Shooter($session, $storageservice);
        switch (pathinfo($scriptfilename, PATHINFO_EXTENSION)) {
            case "json":
                $loader = new JsonScriptLoader();
                $script = $loader->load($scriptfilename, $shooter);
                break;
            case "xml":
                $loader = new XmlScriptLoader();
                $script = $loader->load($scriptfilename, $shooter);
                break;
            default:
                throw new InvalidArgumentException("Unsupported scriptfile extension");
        }
        //print_r($script);
        
        $shooter->runScript($script);
    }
}
