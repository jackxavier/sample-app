<?php

namespace Axmit\UserCore\Entity;

use Axmit\Util\Doctrine\Entity\TimestampTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="user_emails",
 *     indexes={
 *      @ORM\Index(name="user_default_email", columns={"user_id", "is_default"}),
 *     }
 * )
 */
class Email implements EmailInterface
{
    use TimestampTrait;
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var User | UserInterface
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="emails")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    protected $default = false;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->setCreatedOn(new DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return EmailInterface
     */
    public function setId(int $id): EmailInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User | UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface $user
     *
     * @return EmailInterface
     */
    public function setUser(UserInterface $user): EmailInterface
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return EmailInterface
     */
    public function setEmail(string $email): EmailInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param boolean $default
     *
     * @return EmailInterface
     */
    public function setDefault(bool $default = true): EmailInterface
    {
        $this->default = $default;

        return $this;
    }
}
