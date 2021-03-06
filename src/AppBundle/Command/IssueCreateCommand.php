<?php

namespace AppBundle\Command;

use AppBundle\Entity\Issue;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueCreateCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:issue:create')
            ->setDescription('Create a new sprint')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                "Issue's name"
            )
            ->addArgument(
                'complexity',
                InputArgument::REQUIRED,
                "Issue's complexiy"
            )
            ->addOption(
                'sprint',
                's',
                InputOption::VALUE_OPTIONAL,
                "Sprint's name"
            )
            ->addOption(
                'added',
                'a',
                InputOption::VALUE_NONE,
                "Added to sprint after start time"
            )
            ->addOption(
                'completed',
                'c',
                InputOption::VALUE_NONE,
                "Completed"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue creation');
        $issueName = $input->getArgument('name');
        $sprintName = $input->getOption('sprint');
        $sprint = $sprintName ? $this->getSprintRepository()->findOneBy(['name' => $sprintName]) : $this->getSprintRepository()->getCurrentSprint();

        $issue = $this->getIssueRepository()->findOneBy(['name' => $issueName]) ?: new Issue();
        $issue
            ->setName($issueName)
            ->setComplexity((float)$input->getArgument('complexity'))
            ->setAdded($input->getOption('added') ? true : false)
            ->setCompleted($input->getOption('completed') ? true : false)
            ->setCreated(new \DateTime())
            ->setSprint($sprint)
        ;

        $this->getEntityManager()->persist($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Created a new issue '%s' for sprint '%s'", $issue->getName(), $sprint->getName()));
    }
}