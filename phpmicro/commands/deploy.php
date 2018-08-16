<?php

namespace Resgef\SyncList\Commands\Deploy;

use Resgef\SyncList\Lib\SyncList\SyncListApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class DeployCommand extends Command
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
        $this->setName("deploy:to_webscrivania")
            ->setDescription("deploy the synclist to micheles server");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $passthru = function ($cmd) use ($output) {
            \passthru($cmd, $ret);
            if (!$ret) {
                return true;
            } else {
                $output->writeln("Error: command $cmd failed");
                die();
            }
        };
        $sl_conf = $this->synclistapp->config;
        $question_user = new Question("mysql root user:");
        $question_pass = new Question("mysql root password:");
        /** @var QuestionHelper $asker */
        $asker = $this->getHelper('question');
        $mysql_root_user = $asker->ask($input, $output, $question_user);
        $mysql_root_pass = $asker->ask($input, $output, $question_pass);
        $passthru("mysql -u $mysql_root_user -p{$mysql_root_pass} -e "
            . " ' "
            . "create database if not exists '{$sl_conf['db']['synclist']['database']}';"

            . "create user if not exists '{$sl_conf['db']['synclist']['user']}'@'localhost' identified by '{$sl_conf['db']['synclist']['password']}';"

            . "grant all privileges on {$sl_conf['db']['customer']['database']}.* to '{$sl_conf['db']['synclist']['user']}'@'localhost'"

            . "grant all privileges on {$sl_conf['db']['backoffice']['database']}.* to '{$sl_conf['db']['synclist']['user']}'@'localhost'"
            . " ' ");

    }
}