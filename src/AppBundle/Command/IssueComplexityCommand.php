<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueComplexityCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:issue:complexity')
            ->setDescription('Set issue\'s complexity')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Issue name'
            )
            ->addArgument(
                'complexity',
                InputArgument::REQUIRED,
                'Issue\'s complexity'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue complexity');

        $issue = $this->getIssueRepository()->findOneBy(['name' => $input->getArgument('name')]);
        $issue->setComplexity($input->getArgument('complexity'));
        $this->getEntityManager()->persist($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("updated the issue '%s' complexity to ", $issue->getName(), $issue->getComplexity()));
    }
}