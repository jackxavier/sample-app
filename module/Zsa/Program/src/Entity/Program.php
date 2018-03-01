<?php

namespace Zsa\Program\Entity;

use Axmit\UserCore\Entity\User;
use Axmit\Util\Doctrine\Entity\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Collection\AssignedPipsTrait;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Pip\Entity\Relation\PipProgramRelation;
use Zsa\Project\Entity\Project;
use Zsa\Util\Enum\WorkflowStatusEnum;

/**
 * @ORM\Entity
 * @ORM\Table(name="programs")
 */
class Program implements PipContainerInterface
{
    use AssignedPipsTrait;
    use TimestampTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", unique=false, nullable=true)
     *
     * @var string
     */
    protected $description;
    
    /**
     * @ORM\ManyToMany(targetEntity="Zsa\Project\Entity\Project", inversedBy="programs")
     * @ORM\JoinTable(name="program_projects",
     *      joinColumns={@ORM\JoinColumn(name="program_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     *      )
     *
     * @var Collection|Project[]
     */
    protected $projects;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     *
     * @var User
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var User
     */
    protected $updatedBy;

    /**
     * @ORM\Column(name="closed_on", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $closedOn;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     *
     * @var string
     */
    protected $status = WorkflowStatusEnum::VALUE_STATUS_OPENED;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="controller_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var User
     */
    protected $controller;

    /**
     * @ORM\OneToMany(targetEntity="Zsa\Pip\Entity\Relation\PipProgramRelation", mappedBy="program", cascade={"all"}, orphanRemoval=true)
     *
     * @var PipProgramRelation[]
     */
    protected $programPips;

    /**
     * Program constructor.
     */
    public function __construct()
    {
        $this->projects    = new ArrayCollection();
        $this->programPips = new ArrayCollection();
        $this->createdOn   = new \DateTime();
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
     * @return Program
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
     * @return Program
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return Program
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    

    /**
     * @return Collection|Project[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function addProject(Project $project)
    {
        if (!$this->hasProject($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function removeProject(Project $project)
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
        }

        return $this;
    }

    /**
     * @param null|Project|Project[] $projects
     *
     * @return $this
     */
    public function updateProjects(User $controller, $projects = null)
    {
        if (!$projects) {
            $controllersProjects = $this->getControllersProjects($controller);
            foreach ($controllersProjects as $project){
                $this->removeProject($project);
            }

            return $this;
        }

        $programProjects = $this->getControllersProjects($controller)->toArray();

        if (!is_array($projects)) {
            if (!$projects instanceof Project) {
                return $this;
            }

            if ($this->hasProject($projects)) {
                unset($programProjects[$this->projects->indexOf($projects)]);
            } else {
                $this->addProject($projects);
            }
        } else {
            /** @var Project $project */
            foreach ($projects as $project) {
                if (!$project instanceof Project) {
                    continue;
                }

                if ($this->hasProject($project)) {
                    unset($programProjects[$this->projects->indexOf($project)]);
                } else {
                    $this->addProject($project);
                }
            }
        }

        if (!empty($programProjects)) {
            /** @var Project $item */
            foreach ($programProjects as $item) {
                $this->removeProject($item);
            }
        }

        return $this;
    }

    /**
     * @param Project $project
     *
     * @return bool
     */
    public function hasProject(Project $project)
    {
        return $this->projects->contains($project);
    }

    /**
     * @return int
     */
    public function countProjects()
    {
        return $this->projects->count();
    }

    /**
     * @return bool
     */
    public function hasProjects()
    {
        return !$this->projects->isEmpty();
    }
    
    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return Program
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param User $updatedBy
     *
     * @return Program
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
        return self::getAvailableStatuses()[$this->status];
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
        return $this->closedOn;
    }

    /**
     * @param \DateTime $closedOn
     *
     * @return Program
     */
    public function setClosedOn($closedOn)
    {
        $this->closedOn = $closedOn;

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
     * @return User
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param User $controller
     *
     * @return Program
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param User $user
     *
     * @return ArrayCollection|Collection
     */
    public function getUserProjects(User $user)
    {
        $projects         = $this->getProjects();
        $userProjects = $projects->filter(
            function (Project $project) use ($user) {
                $attendee   = $project->getProjectAttendeeByUser($user);
                $controller = $project->getController()->getId() === $user->getId();

                return $attendee || $controller;
            }
        );

        return $userProjects;
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
     * @return PipProgramRelation
     */
    public function addPip($pip, $type = null)
    {
        if ($this->hasPip($pip, $type)) {
            return null;
        }

        $pipRelation = new PipProgramRelation($this, $pip, $type);
        $this->programPips->add($pipRelation);
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
            $this->programPips->removeElement($relation);
            $pip->removeRelation($relation);
        }

        return $this;
    }

    /**
     * @return Collection|PipRelation[]
     */
    public function getPips()
    {
        return $this->programPips;
    }

    /**
     * @return array
     */
    public function getProgramPips()
    {
        return $this->getAssignedPips();
    }

    /**
     * @param User $user
     *
     * @return ArrayCollection|Collection
     */
    public function getControllersProjects(User $user)
    {

        return $this->projects->filter(
            function (Project $project) use ($user) {
                return $project->getController()->getId() === $user->getId();
            }
        );
    }

    /**
     * @return bool
     */
    public function hasPips()
    {
        return (bool)count($this->getPips()) > 0;
    }
}