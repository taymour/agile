<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintCreateCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:sprint:create')
            ->setDescription('Create a new sprint')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Sprint name'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Sprint creation');

        $sprint = (new Sprint())
            ->setName($input->getArgument('name'))
            ->setCreated(new \DateTime())
        ;
        $this->getEntityManager()->persist($sprint);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Created a new sprint '%s'", $sprint->getName()));
    }
}