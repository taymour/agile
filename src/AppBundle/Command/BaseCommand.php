<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class BaseCommand extends ContainerAwareCommand
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return \AppBundle\Repository\SprintRepository
     */
    protected function getSprintRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Sprint');
    }

    /**
     * @return \AppBundle\Repository\IssueRepository
     */
    protected function getIssueRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Issue');
    }
}
