<?php

namespace AppBundle\Command;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Sprint;
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Issue creation');
        $sprintName = $input->getOption('sprint');
        $sprint = $sprintName ? $this->getSprintRepository()->findOneBy(['name' => $sprintName]) : $this->getSprintRepository()->getCurrentSprint();


        $issue = (new Issue())
            ->setName($input->getArgument('name'))
            ->setComplexity((float)$input->getArgument('complexity'))
            ->setCreated(new \DateTime())
            ->setSprint($sprint)
        ;

        $this->getEntityManager()->persist($issue);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Created a new issue '%s' for sprint '%s'", $issue->getName(), $sprint->getName()));
    }
}