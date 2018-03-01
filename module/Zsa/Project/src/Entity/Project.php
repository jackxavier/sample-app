<?php

namespace Zsa\Project\Entity;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Axmit\Util\Doctrine\Entity\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Collection\AssignedPipsTrait;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Pip\Entity\Relation\PipProjectRelation;
use Zsa\Program\Entity\Program;
use Zsa\Util\Enum\WorkflowStatusEnum;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 */
class Project implements PipContainerInterface
{
    use ArrayOrTraversableGuardTrait;
    use AssignedPipsTrait;
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="ProjectAttendee", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     *
     * @var ProjectAttendee[]
     */
    protected $attendees;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     *
     * @var int
     */
    protected $status = WorkflowStatusEnum::VALUE_STATUS_OPENED;

    /**
     * @ORM\Column (name = "closed_on", type = "datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $closed_on;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     *
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var UserInterface
     */
    protected $updatedBy;

    /**
     * @ORM\ManyToMany(targetEntity="Zsa\Program\Entity\Program", mappedBy="projects", cascade={"all"}, orphanRemoval=true)
     *
     * @var Program[]
     */
    protected $programs;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="controller_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var User
     */
    protected $controller;

    /**
     * @ORM\OneToMany(targetEntity="Zsa\Pip\Entity\Relation\PipProjectRelation", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     *
     * @var PipProjectRelation[]
     */
    protected $projectPips;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->createdOn     = new \DateTime();
        $this->programs      = new ArrayCollection();
        $this->projectPips   = new ArrayCollection();
        $this->attendees     = new ArrayCollection();
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
     * @return Project
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Project
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return $this
     */
    public function addProjectAttendee(ProjectAttendee $projectAttendee)
    {
        if (!$this->attendees->contains($projectAttendee)) {
            $this->attendees->add($projectAttendee);
            $projectAttendee->setProject($this);
        }

        return $this;
    }

    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return $this
     */
    public function removeProjectAttendee(ProjectAttendee $projectAttendee)
    {
        if ($this->attendees->contains($projectAttendee)) {
            $this->attendees->removeElement($projectAttendee);
        }

        return $this;
    }

    /**
     * @return Collection|ProjectAttendee[]
     */
    public function getProjectAttendees()
    {
        return $this->attendees;
    }

    /**
     * @return bool
     */
    public function hasProjectAttendees()
    {
        return (bool)$this->countProjectAttendees() > 0;
    }

    /**
     * @return int
     */
    public function countProjectAttendees()
    {
        return count($this->getProjectAttendees());
    }

    /**
     * @param User $user
     *
     * @return ProjectAttendee|mixed|null
     */
    public function getProjectAttendeeByUser($user)
    {
        if (!$user->getId()) {
            return null;
        }

        $attendees = $this->attendees->filter(
            function (ProjectAttendee $projectAttendee) use ($user) {
                return (int)$projectAttendee->getUser()->getId() === (int)$user->getId();
            }
        );

        if ($attendees->isEmpty()) {
            return null;
        }

        return current($attendees->toArray());
    }

    /**
     * @param User[]|\Traversable $attendees
     *
     * @return $this
     */
    public function updateProjectAttendees($attendees = null)
    {
        if (!$attendees) {
            $this->attendees->clear();

            return $this;
        }

        $projectAttendees = $this->attendees->toArray();

        if (!is_array($attendees)) {
            if (!$attendees instanceof User) {
                return $this;
            }

            $projectAttendee = $this->getProjectAttendeeByUser($attendees);

            if ($projectAttendee) {
                unset($projectAttendees[$this->attendees->indexOf($projectAttendee)]);
            } else {
                $attendee = new ProjectAttendee($attendees);
                $this->addProjectAttendee($attendee);
            }

            return $this;
        }

        /** @var User $user */
        foreach ($attendees as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $projectAttendee = $this->getProjectAttendeeByUser($user);

            if ($projectAttendee) {
                unset($projectAttendees[$this->attendees->indexOf($projectAttendee)]);
            } else {
                $attendee = new ProjectAttendee($user);
                $this->addProjectAttendee($attendee);
            }
        }

        if (!empty($projectAttendees)) {
            /** @var ProjectAttendee $item */
            foreach ($projectAttendees as $item) {
                $this->removeProjectAttendee($item);
            }
        }

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     *
     * @return Project
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface $updatedBy
     *
     * @return Project
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return WorkflowStatusEnum::convertStatusToString($this->status);
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status = null)
    {
        if (!$status) {
            $this->status = WorkflowStatusEnum::VALUE_STATUS_OPENED;

            return $this;
        }

        $this->status = WorkflowStatusEnum::convertStatusToInt($status);

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getClosedOn()
    {
        return $this->closed_on;
    }

    /**
     * @param \DateTime $closed_on
     *
     * @return Project
     */
    public function setClosedOn($closed_on)
    {
        $this->closed_on = $closed_on;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->status == WorkflowStatusEnum::VALUE_STATUS_CLOSED;
    }

    /**
     * @return Program|mixed
     */
    public function getProgram()
    {
        return $this->programs->current();
    }

    /**
     * @param Program $program
     *
     * @return Project
     * @throws \Exception
     */
    public function setProgram(Program $program = null)
    {
        if (!$program) {
            if ($this->hasProgram()) {
                $this->getProgram()->removeProject($this);
            }

            return $this;
        }

        if ($this->programs->contains($program)) {
            return $this;
        }

        if ($this->hasProgram()) {
            $this->getProgram()->removeProject($this);
        }

        $this->programs->add($program);
        $program->addProject($this);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasProgram()
    {
        return !$this->programs->isEmpty();
    }

    /**
     * @return Program[]
     */
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * @param Program[] $programs
     *
     * @return Project
     */
    public function setPrograms($programs)
    {
        $this->programs = $programs;

        return $this;
    }

    /**
     * @return User
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param User $controller
     *
     * @return Project
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return WorkflowStatusEnum::getAvailableStatuses();
    }

    /**
     * @param Pip  $pip
     * @param null $type
     *
     * @return PipProjectRelation
     */
    public function addPip($pip, $type = null)
    {
        if ($this->hasPip($pip, $type)) {
            return null;
        }

        $pipRelation = new PipProjectRelation($this, $pip, $type);
        $this->projectPips->add($pipRelation);
        $pip->addRelation($pipRelation);

        return $pipRelation;
    }

    /**
     * @param Pip  $pip
     * @param null $type
     *
     * @return $this
     */
    public function removePip($pip, $type = null)
    {
        if ($this->hasPip($pip, $type)) {
            $relation = $this->getPip($pip, $type);
            $this->projectPips->removeElement($relation);
            $pip->removeRelation($relation);
        }

        return $this;
    }

    /**
     * @return Collection|PipRelation[]
     */
    public function getPips()
    {
        return $this->projectPips;
    }

    /**
     * @return bool
     */
    public function hasPips()
    {
        return (bool)count($this->getPips()) > 0;
    }

    /**
     * @return array
     */
    public function getProjectPips()
    {
        return $this->getAssignedPips();
    }

    /**
     * @param $type
     *
     * @return ArrayCollection|Collection
     */
    public function getPipRelationsByType($type)
    {
        $criteria = Criteria::create();
        $criteria->where($criteria::expr()->eq('type', $type));
        $pipRelations = $this->projectPips->matching($criteria);

        if($pipRelations->isEmpty()){
            return $pipRelations;
        }

        $pips = $pipRelations->map(function (PipRelation $relation){
            return $relation->getPip();
        });

        return $pips->filter(
            function (Pip $pip) {
                return $pip->getParentPip() === null;
            }
        );
    }
}