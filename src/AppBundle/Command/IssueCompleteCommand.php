<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
                'Issue name'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue completion');

        $issue = $this->getIssueRepository()->findOneBy(['name' => $input->getArgument('name')]);
        $issue->setCompleted(true);
        $this->getEntityManager()->persist($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Complete the issue with name '%s'", $issue->getName()));
    }
}