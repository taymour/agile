<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueAddedCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:issue:added')
            ->setDescription('Set issue as added to sprint after start time')
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
        $io->title('Issue add');

        $issue = $this->getIssueRepository()->findOneBy(['name' => $input->getArgument('name')]);
        $issue->setAdded(true);
        $this->getEntityManager()->persist($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Set the issue with name '%s' as added to sprint after start time", $issue->getName()));
    }
}