<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sprint;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprintDeleteCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('agile:sprint:delete')
            ->setDescription('Delete a sprint')
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
        $io->title('Sprint deletion');

        $sprint = $this->getSprintRepository()->findOneBy(['name' => $input->getArgument('name')]);
        $this->getEntityManager()->remove($sprint);
        $this->getEntityManager()->flush();

        $io->success(sprintf("Delete the sprint with name '%s'", $sprint->getName()));
    }
}