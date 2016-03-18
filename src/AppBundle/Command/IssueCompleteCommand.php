<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueCompleteCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:issue:complete')
            ->setDescription('Complete an issue')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Issues names separated by commas'
            )
            ->addOption(
                'uncompleted',
                'u',
                InputOption::VALUE_NONE,
                "Uncompleted"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue completion');

        $issues = $this->getIssueRepository()->findBy(['name' => explode(',', $input->getArgument('name'))]);

        foreach ($issues as $issue) {
            $issue->setCompleted($input->getOption('uncompleted') ? false : true);
            $this->getEntityManager()->persist($issue);
        }

        $this->getEntityManager()->flush();

        foreach ($issues as $issue) {
            $io->success(sprintf("Complete the issue with name '%s'", $issue->getName()));
        }
    }
}