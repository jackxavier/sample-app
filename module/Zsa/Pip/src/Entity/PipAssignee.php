<?php

namespace Zsa\Pip\Entity;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Entity\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PipAssignee
 *
 * @ORM\Entity
 * @ORM\Table(name="pip_assignees")
 */
class PipAssignee
{
    const VALUE_STATUS_CONTROLLER = 'controller';
    const VALUE_STATUS_SOLVER     = 'solver';
    const VALUE_STATUS_OWNER      = 'owner';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")ø
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $status = self::VALUE_STATUS_SOLVER;

    /**
     * @var Pip
     *
     * @ORM\ManyToOne(targetEntity="Zsa\Pip\Entity\Pip", inversedBy="assignees")
     * @ORM\JoinColumn(name="pip_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $pip;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="Axmit\UserCore\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    protected $user;

    /**
     * PipAssignee constructor.
     *
     * @param Pip    $pip
     * @param User   $user
     * @param string $status
     */
    public function __construct(Pip $pip, User $user, $status = self::VALUE_STATUS_SOLVER)
    {
        $this->pip    = $pip;
        $this->user   = $user;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return PipAssignee
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
     * @return PipAssignee
     */
    public function setPip($pip)
    {
        $this->pip = $pip;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return PipAssignee
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
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
     * @return PipAssignee
     */
    public function setStatus($status)
    {
        if (in_array($status, self::getAvailableStatuses())) {
            $this->status = $status;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSolver()
    {
        return $this->status == self::VALUE_STATUS_SOLVER;
    }

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return [
            self::VALUE_STATUS_CONTROLLER,
            self::VALUE_STATUS_SOLVER,
            self::VALUE_STATUS_OWNER,
        ];
    }

}