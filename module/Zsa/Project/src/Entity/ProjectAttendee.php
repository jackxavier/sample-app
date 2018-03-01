<?php

namespace Zsa\Project\Entity;

use Axmit\UserCore\Entity\User;
use Axmit\Util\Doctrine\Entity\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="project_attendees")
 */
class ProjectAttendee
{
    use TimestampTrait;
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Zsa\Project\Entity\Project", inversedBy="attendees")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @var Project
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(name="created_on", type="datetime")
     *
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * ProjectAttendee constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->createdOn = new \DateTime();
        $this->user      = $user;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return ProjectAttendee
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return ProjectAttendee
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return ProjectAttendee
     */
    public function setUser(User $user): ProjectAttendee
    {
        $this->user = $user;

        return $this;
    }
}