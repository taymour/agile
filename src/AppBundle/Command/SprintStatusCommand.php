<?php

namespace AppBundle\Command;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintStatusCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:sprint:status')
            ->setDescription('Show sprint status')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                "Sprint's name"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $sprintName = $input->getOption('name');
        $sprint = $sprintName ? $this->getSprintRepository()->findOneBy(['name' => $sprintName]) : $this->getSprintRepository()->getCurrentSprint();

        $io->title(sprintf("Sprint '%s'", $sprint->getName()));

        $io->section('Initial Issues');
        $this->showIssues($this->getIssueRepository()->getNonAdded($sprint), $io);

        $io->section('Issue added to sprint after start time');
        $this->showIssues($this->getIssueRepository()->getAdded($sprint), $io);

        $stats = [
            sprintf('<info>%s</info> Total issues', $this->getIssueRepository()->countTotal($sprint)),
            sprintf('<info>%s</info> Added issues', $this->getIssueRepository()->countTotal($sprint, true)),
            sprintf('<info>%s</info> Completed issues', $this->getIssueRepository()->countTotalCompleted($sprint)),
            sprintf('<info>%s</info> Not completed issues', $this->getIssueRepository()->countTotalCompleted($sprint, false)),
            sprintf('<info>%s</info> Initial total complexity', $this->getIssueRepository()->countTotalComplexity($sprint)),
            sprintf('<info>%s</info> Initial Completed complexity', $this->getIssueRepository()->countTotalComplexity($sprint, true)),
            sprintf('<info>%s</info> Added after sprint start total complexity', $this->getIssueRepository()->countTotalComplexity($sprint, false, true)),
            sprintf('<info>%s</info> Added after sprint start Completed complexity', $this->getIssueRepository()->countTotalComplexity($sprint, true, true)),
        ];

        $io->section('Summary');
        $io->listing($stats);
    }

    /**
     * @param Issue[] $issues
     * @param SymfonyStyle $io
     */
    protected function showIssues($issues, SymfonyStyle $io)
    {
        $rows = [];

        foreach ($issues as $issue) {
            $row = [
                $issue->getName(),
                $issue->getComplexity(),
                $issue->getCreated()->format('d/m/Y'),
            ];

            if ($issue->getCompleted()) {
                $this->decorateWithInfo($row);
            }

            $rows[] = $row;
        }

        $io->table([
            'Name',
            'Complexity',
            'Created',
        ], $rows);
    }

    protected function decorateWithInfo(array &$table)
    {
        foreach ($table as $index => $row) {
            $table[$index] = sprintf('<info>%s</info>', $row);
        }
    }
}