<?php

namespace AppBundle\Command;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintSyncCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:sprint:sync')
            ->setDescription('Synchronize sprint using api')
            ->addOption(
                'name',
                null,
                InputOption::VALUE_OPTIONAL,
                "Sprint's name"
            )
            ->addOption(
                'init',
                'i',
                InputOption::VALUE_NONE,
                "Init sprint"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Sprint Sync');

        $sprintName = $input->getOption('name');
        $sprint = $sprintName ? $this->getSprintRepository()->findOneBy(
            ['name' => $sprintName]
        ) : $this->getSprintRepository()->getCurrentSprint();

        if ($input->getOption('init')) {
            $io->section('Sprint reset');

            foreach ($sprint->getIssues() as $issue) {
                $this->getEntityManager()->remove($issue);
            }

            $this->getEntityManager()->flush();

            $io->success(sprintf('Removed all issues from sprint with name "%s"', $sprint->getName()));

            $io->section('Sprint sync');
        }

        $connector = $this->getContainer()->get('agile.connector.jira');
        $issues = $connector->getSprintIssues($sprint->getName());

        $this->updateSprintIssues($sprint, $issues, $input->getOption('init'));

        $this->getEntityManager()->persist($sprint);
        $this->getEntityManager()->flush();
        $io->success(sprintf('Synced sprint with name "%s"', $sprint->getName()));
    }

    /**
     * @param Sprint $sprint
     * @param Issue[] $issues
     * @param bool $init
     */
    protected function updateSprintIssues(Sprint $sprint, array $issues, $init = false)
    {
        if ($init) {
            $sprint->setIssues($issues);
            return;
        }

        foreach ($issues as $issue) {
            $sprintIssue = $sprint->findIssueByName($issue->getName());

            if ($sprintIssue) {
                $sprintIssue->setCompleted($issue->getCompleted());
            } else {
                $issue->setAdded(true);
                $sprint->addIssue($issue);
            }
        }
    }
}