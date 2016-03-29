<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issue
 *
 * @ORM\Table(name="issue")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IssueRepository")
 */
class Issue
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
     * @var float
     *
     * @ORM\Column(name="complexity", type="float")
     */
    private $complexity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     */
    private $created;

    /**
     * @var bool
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="added", type="boolean")
     */
    private $added = false;

    /**
     * @var Sprint
     *
     * @ORM\ManyToOne(targetEntity="Sprint", inversedBy="issues")
     */
    private $sprint;

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
     * @return Issue
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
     * Set complexity
     *
     * @param float $complexity
     *
     * @return Issue
     */
    public function setComplexity($complexity)
    {
        $this->complexity = $complexity;

        return $this;
    }

    /**
     * Get complexity
     *
     * @return float
     */
    public function getComplexity()
    {
        return $this->complexity;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Issue
     */
    public function setCreated($created)
    {
        if (!$this->created) {
            $this->created = $created;
        }

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
     * Set completed
     *
     * @param boolean $completed
     *
     * @return Issue
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return bool
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @return Sprint
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * @param Sprint $sprint
     *
     * @return $this
     */
    public function setSprint(Sprint $sprint)
    {
        $this->sprint = $sprint;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAdded()
    {
        return $this->added;
    }

    /**
     * @param boolean $added
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }
}

