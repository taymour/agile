<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintListCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:sprint:list')
            ->setDescription('List all sprints')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Sprint list');
        $io->table(['id', 'name', 'created'], $this->getSprintRepository()->getAllAsArray());
    }
}