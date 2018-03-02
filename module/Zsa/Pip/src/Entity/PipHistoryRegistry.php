<?php

namespace Zsa\Pip\Entity;

use Axmit\UserCore\Entity\UserInterface;
use Axmit\Util\Doctrine\Entity\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pip_history_registry")
 */
class PipHistoryRegistry
{
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
     * @ORM\ManyToOne(targetEntity="Pip")
     * @ORM\JoinColumn(name="refers_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     *
     * @var Pip
     */
    protected $pip;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $action;

    /**
     * @ORM\Column(name="activity_type", type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected $activityType;

    /**
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * PipHistoryRegistry constructor.
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
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
     * @return PipHistoryRegistry
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Pip
     */
    public function getPip()
    {
        return $this->pip;
    }

    /**
     * @param Pip $pip
     *
     * @return PipHistoryRegistry
     */
    public function setPip($pip)
    {
        $this->pip = $pip;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return PipHistoryRegistry
     */
    public function setAction($action)
    {
        $this->action = $action;

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
     * @return PipHistoryRegistry
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityType()
    {
        return $this->activityType;
    }

    /**
     * @param string $activityType
     *
     * @return PipHistoryRegistry
     */
    public function setActivityType($activityType)
    {
        $this->activityType = $activityType;

        return $this;
    }
}