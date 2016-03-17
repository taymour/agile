<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sprint
 *
 * @ORM\Table(name="sprint")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SprintRepository")
 */
class Sprint
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;

    /**
     * @var Issue[]
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="sprint")
     */
    private $issues;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Sprint
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Sprint
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param array $issues
     * @return $this
     */
    public function setIssues(array $issues)
    {
        $this->issues = $issues;

        return $this;
    }
}

