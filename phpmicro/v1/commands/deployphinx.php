<?php

namespace Resgef\SyncList\Commands\DeployPhinx;

use Resgef\SyncList\Lib\SyncList\SyncListApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class DeployPhinxCommand extends Command
{
    /** @var SyncListApp $synclistapp */
    private $synclistapp;

    function __construct(SyncListApp $app)
    {
        parent::__construct(null);
        $this->synclistapp = $app;
    }

    protected function configure()
    {
        $this->setName("deploy:phinx")
            ->setDescription("set database config in phinx.yml")
            ->addArgument('name', InputArgument::REQUIRED, 'the entry name in the config file for the database you want');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $root_path = $this->synclistapp->config['root_path'];
        $phinx_config_file = "$root_path/phinx.yml";
        $name = $input->getArgument('name');
        if (!$name || !array_key_exists($name, $this->synclistapp->config['db'])) {
            $output->writeln("Error: provided name '$name' invalid!");
            return 1;
        }
        $db_creds = $this->synclistapp->config['db'][$name];
        $yml = new Parser();
        $phinxconf = $yml->parse(file_get_contents($phinx_config_file));
        $phinxconf['environments']['default_database'] = $name;
        $phinxconf['environments'][$name] = [
            'adapter' => 'mysql',
            'host' => $db_creds['host'],
            'name' => $db_creds['database'],
            'user' => $db_creds['user'],
            'pass' => $db_creds['password'],
            'port' => $db_creds['port'],
            'charset' => 'utf8',
        ];
        $dumper = new Dumper();
        file_put_contents($phinx_config_file, $dumper->dump($phinxconf));
        $output->writeln("phinx config $phinx_config_file fillup success!");
        return 0;
    }
}