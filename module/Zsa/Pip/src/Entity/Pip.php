<?php

namespace Zsa\Pip\Entity;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Axmit\Util\Doctrine\Entity\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Collection\AssignedPipsTrait;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dto\Pip\PipEditTo;
use Zsa\Pip\Entity\Enum\PipRelationsEnum;
use Zsa\Pip\Entity\Enum\PipStatusEnum;
use Zsa\Pip\Entity\Relation\PipSelfRelation;
use Zsa\Pip\Util\ObjectComparativeCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pips")
 */
class Pip implements PipContainerInterface
{
    use AssignedPipsTrait;
    use TimestampTrait;

    const VALUE_IMPROVEMENT = 'improvement';
    const VALUE_PROBLEM     = 'problem';

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $body;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $priority = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $type = self::VALUE_IMPROVEMENT;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    protected $status = PipStatusEnum::VALUE_BACKLOG;

    /**
     * @ORM\OneToMany(targetEntity="Zsa\Pip\Entity\PipRelation", mappedBy="pip", cascade={"all"}, orphanRemoval=true)
     *
     * @var PipRelation[]
     */
    protected $relations;

    /**
     * @ORM\OneToMany(targetEntity="Zsa\Pip\Entity\Relation\PipSelfRelation", mappedBy="self", cascade={"all"}, orphanRemoval=true)
     *
     * @var PipSelfRelation[]
     */
    protected $relatedPips;

    /**
     * @ORM\ManyToOne(targetEntity="Pip", inversedBy="childrenPips")
     * @ORM\JoinColumn(name="parent_pip_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     *
     * @var Pip
     */
    protected $parentPip;

    /**
     * @ORM\OneToMany(targetEntity="Pip", mappedBy="parentPip")
     *
     * @var Pip[]
     */
    protected $childrenPips;

    /**
     * @ORM\OneToMany(targetEntity="PipAssignee", mappedBy="pip", cascade={"all"}, orphanRemoval=true)
     *
     * @var PipAssignee[]
     */
    protected $assignees;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
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
     * Pip constructor.
     */
    public function __construct()
    {
        $this->createdOn    = new \DateTime();
        $this->assignees    = new ArrayCollection();
        $this->relatedPips  = new ArrayCollection();
        $this->childrenPips = new ArrayCollection();
        $this->relations    = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return Pip
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Pip
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     *
     * @return Pip
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Pip
     */
    public function setType($type = null)
    {
        if ($type) {
            $this->type = $type;

            return $this;
        }

        $this->type = self::VALUE_PROBLEM;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAvailableTypes()
    {
        return [
            static::VALUE_IMPROVEMENT,
            static::VALUE_PROBLEM,
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Pip
     */
    public function setStatus($status)
    {
        if (!in_array($status, PipStatusEnum::getSupportedStatuses())) {
            throw new \OutOfBoundsException(
                sprintf('Type %s is not supported', $status)
            );
        }

        $this->status = $status;

        return $this;
    }

    /**
     * @return Pip
     */
    public function getParentPip()
    {
        return $this->parentPip;
    }

    /**
     * @param Pip|null $parentPip
     *
     * @return Pip
     */
    public function setParentPip($parentPip = null)
    {
        $this->parentPip = $parentPip;

        return $this;
    }

    /**
     * @param Pip $child
     *
     * @return $this
     */
    public function addChild(Pip $child)
    {
        if (!$this->childrenPips->contains($child)) {
            $this->childrenPips->add($child);
            $child->setParentPip($this);
        }

        return $this;
    }

    /**
     * @param Pip $child
     *
     * @return $this
     */
    public function removeChild(Pip $child)
    {
        if ($this->childrenPips->contains($child)) {
            $this->childrenPips->removeElement($child);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeChildren()
    {
        foreach ($this->childrenPips as $pip) {
            $pip->setParentPip(null);
            $this->childrenPips->removeElement($pip);
        }

        return $this;
    }

    /**
     * @return Collection|Pip[]
     */
    public function getChildrenPips()
    {
        return $this->childrenPips;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (bool)count($this->getChildrenPips()) > 0;
    }

    /**
     * @return int
     */
    public function countChildren()
    {
        return count($this->getChildrenPips());
    }

    /**
     * @param $owner
     *
     * @return bool
     */
    public function isOwner($owner)
    {
        if ($owner instanceof UserInterface) {
            return $this->getOwner()->getId() === $owner->getId();
        }

        return false;
    }

    /**
     * @return UserInterface|User
     */
    public function getOwner()
    {
        return $this->getCreatedBy();
    }

    /**
     * @param mixed $owner
     *
     * @return $this
     */
    public function setOwner($owner)
    {
        if (!$owner instanceof UserInterface && !$owner instanceof User) {
            return $this;
        }

        $criteria = Criteria::create();
        $criteria->where(
            $criteria->expr()->eq('status', PipAssignee::VALUE_STATUS_OWNER)
        );

        $oldOwner = $this->assignees->matching($criteria)->current();

        if ($oldOwner) {
            $this->removeAssignee($oldOwner);
        }

        $this->addAssignee($owner, PipAssignee::VALUE_STATUS_OWNER);
        $this->setCreatedBy($owner->getUser());

        return $this;
    }

    /**
     * @return PipAssignee
     */
    public function getSolver()
    {
        return $this->getAssignees()->filter(
            function (PipAssignee $pipAssignee) {
                return $pipAssignee->isSolver();
            }
        )->current();
    }

    /**
     * @param UserInterface $solver
     *
     * @return bool
     */
    public function isSolver($solver)
    {
        return (int)$solver->getId() === (int)$this->getSolver()->getId();

    }

    /**
     * @param User   $user
     * @param string $status
     *
     * @return $this
     */
    public function addAssignee(User $user, $status = PipAssignee::VALUE_STATUS_SOLVER)
    {
        $this->assignees->add(new PipAssignee($this, $user, $status));

        return $this;
    }

    /**
     * @param PipAssignee $assignee
     *
     * @return $this
     */
    public function removeAssignee(PipAssignee $assignee)
    {
        if ($this->assignees->contains($assignee)) {
            $this->assignees->removeElement($assignee);
        }

        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return Collection|PipAssignee[]
     */
    public function getAssignees(User $user = null)
    {
        if (!$user) {
            return $this->assignees;
        }

        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('user', $user));

        return $this->assignees->matching($criteria);
    }

    /**
     * @return int
     */
    public function countAssignees()
    {
        return count($this->getAssignees());
    }

    /**
     * @param null $status
     *
     * @return array|Collection|PipAssignee[]
     */
    public function getPipAssigneesWithStatus($status = null)
    {
        if (!$status) {
            return $this->getAssignees();
        }

        $assignees = array_filter(
            $this->getAssignees()->toArray(),
            function (PipAssignee $pipAssignee) use ($status) {
                return $pipAssignee->getStatus() == $status;
            }
        );

        return $assignees;
    }

    /**
     * @return bool
     */
    public function hasAssignees()
    {
        return !$this->assignees->isEmpty();
    }

    /**
     * @param $assigned
     *
     * @return bool
     */
    public function isAssignedToPip($assigned)
    {
        if (!$this->hasAssignees()) {
            return false;
        }

        if (!$assigned instanceof UserInterface) {
            return false;
        }

        $users = array_filter(
            $this->getAssignees()->toArray(),
            function (PipAssignee $pipAssignee) use ($assigned) {
                return $pipAssignee->getUser()->getId() === $assigned->getId();
            }
        );

        return count($users) > 0;
    }

    /**
     * @param $controller
     *
     * @return mixed|null
     */
    public function getController($controller)
    {
        if (!$controller instanceof UserInterface) {
            return null;
        }

        $criteria = Criteria::create();
        $criteria->where(
            $criteria->expr()->andX(
                $criteria->expr()->eq('status', PipAssignee::VALUE_STATUS_CONTROLLER),
                $criteria->expr()->eq('user', $controller)
            )
        );

        return $this->assignees->matching($criteria)->current();
    }

    /**
     * @return UserInterface|User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface|User $createdBy
     *
     * @return Pip
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return UserInterface|User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface|User $updatedBy
     *
     * @return Pip
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @param $pip
     *
     * @return $this
     */
    public function bindPip($pip)
    {
        if (!$pip instanceof Pip && !$pip instanceof PipEditTo) {
            return $this;
        }

        if (!empty($pip->getTitle())) {
            $this->setTitle($pip->getTitle());
        }

        if (!empty($pip->getPriority())) {
            $this->setPriority($pip->getPriority());
        }

        if (!empty($pip->getType())) {
            $this->setType($pip->getType());
        }

        $this->setParentPip($pip->getParent())
             ->setBody($pip->getBody());

        return $pip;
    }

    /**
     * @param Pip  $pip
     * @param null $type
     *
     * @return PipSelfRelation
     */
    public function addPip($pip, $type = null)
    {
        if ($this->hasPip($pip, $type)) {
            return null;
        }
        $pipRelation = new PipSelfRelation($this, $pip, $type);
        $this->relatedPips->add($pipRelation);
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
            $this->relatedPips->removeElement($relation);
            $pip->removeRelation($relation);
        }

        return $this;
    }

    /**
     * @return Collection|PipRelation[]
     */
    public function getPips()
    {
        return $this->relatedPips;
    }

    /**
     * @return array
     */
    public function getRelatedPips()
    {
        return $this->relatedPips;
    }

    /**
     * @return bool
     */
    public function hasPips()
    {
        return (bool)count($this->getPips()) > 0;
    }

    /**
     * @return Pip[]
     */
    public function getAllChildren()
    {
        $pipChildren = [];
        /** @var self $child */
        foreach ($this->getChildrenPips() as $child) {
            if ($child->hasChildren()) {
                $pipChildren = array_merge($pipChildren, $child->getAllChildren());
            }
            array_push($pipChildren, $child);
        }

        return $pipChildren;
    }

    /**
     * @return ArrayCollection|PipRelation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @return bool
     */
    public function hasRelations()
    {
        return (bool)count($this->getRelations()) > 0;
    }

    /**
     * @param PipRelation $pipRelation
     *
     * @return $this
     */
    public function addRelation(
        PipRelation $pipRelation
    ) {
        if (!$this->relations->contains($pipRelation)) {
            $this->relations->add($pipRelation);
        }

        return $this;
    }

    /**
     * @param PipRelation $pipRelation
     *
     * @return $this
     */
    public function removeRelation(
        PipRelation $pipRelation
    ) {
        if ($this->relations->contains($pipRelation)) {
            $this->relations->removeElement($pipRelation);
        }

        return $this;
    }

    /**
     * @return $this|Pip
     */
    public function getAbsoluteParent()
    {
        if ($this->getParentPip() !== null) {
            return $this->getParentPip()->getAbsoluteParent();
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isInBacklog()
    {
        return $this->status == PipStatusEnum::VALUE_BACKLOG;
    }

    /**
     * @return bool
     */
    public function isOpened()
    {
        return $this->status == PipStatusEnum::VALUE_OPENED;
    }

    /**
     * @param PipRelation $pipRelation
     *
     * @return bool
     */
    public function hasRelation(PipRelation $pipRelation)
    {
        $relations = $this->relations->filter(
            function (PipRelation $relation) use ($pipRelation) {
                if (get_class($relation) === get_class($pipRelation)) {
                    return $relation->getRelated()->getId() === $pipRelation->getRelated()->getId();
                }

                return false;
            }
        );

        return (bool)count($relations) > 0;
    }

    /**
     * @return bool
     */
    public function hasSolvingRelations()
    {
        $relations = $this->relations->filter(
            function (PipRelation $relation) {
                return $relation->getType() == PipRelationsEnum::VALUE_SOLVES;
            }
        );

        return (bool)count($relations) > 0;
    }

    /**
     * @return PipRelation[]|mixed
     */
    public function getAllRelations()
    {
        $relations = new ObjectComparativeCollection($this->relations->toArray());

        if (!$this->parentPip) {
            return $relations;
        }

        $parentRelations = $this->parentPip->getAllRelations();

        foreach ($parentRelations as $parentRelation) {
            if (!$relations->contains(
                $parentRelation,
                function (PipRelation $relation, PipRelation $parentRelation) {
                    return $relation->getType() == $parentRelation->getType()
                           && $relation->getRelated()->getId() == $parentRelation->getRelated()->getId()
                           && get_class($relation->getRelated()) == get_class($parentRelation->getRelated());
                }
            )) {
                $relations->add($parentRelation);
            }
        }

        return $relations;
    }

}