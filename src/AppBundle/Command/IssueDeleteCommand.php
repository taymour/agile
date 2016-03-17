<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueDeleteCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:issue:delete')
            ->setDescription('Delete an issue')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                "Issue's name"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue deletion');

        $issue = $this->getIssueRepository()->findOneBy(['name' => $input->getArgument('name')]);
        $this->getEntityManager()->remove($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Delete the issue with name '%s'", $issue->getName()));
    }
}