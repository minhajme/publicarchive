<?php

namespace Resgef\SyncList\Commands\CreateUser;

use Resgef\SyncList\Helpers\PasswordHelpers\PasswordHelpers;
use Resgef\SyncList\Lib\SyncList\SyncListApp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
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
        $this->setName("user:create")
            ->setDescription("create a regular user");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $q_user = new Question("username: ");
        $q_pass = new Question("password: ");
        $username = $helper->ask($input, $output, $q_user);

        if ($this->synclistapp->model->user_exist($username)) {
            $output->writeln("<error>username exist!</error>");
            return 1;
        }

        $password = $helper->ask($input, $output, $q_pass);
        $salt = time();
        $hash = PasswordHelpers::make_hash($salt, $password);

        if (!$this->synclistapp->model->save_user($username, $hash, $salt)) {
            $output->writeln("<error>failed</error>");
        } else {
            $output->writeln("<info>success</info>");
        }
    }
}