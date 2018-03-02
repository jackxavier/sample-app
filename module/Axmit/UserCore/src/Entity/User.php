<?php

namespace Axmit\UserCore\Entity;

use Axmit\Util\Doctrine\Entity\TimestampTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    use ArrayOrTraversableGuardTrait;
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
     * @var Collection|EmailInterface[]
     *
     * @ORM\OneToMany(targetEntity="Email", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     */
    protected $emails;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $password;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setCreatedOn(new DateTime());
        $this->emails = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserInterface
     */
    public function setId(int $id): UserInterface
    {
        throw new \BadMethodCallException('User entity is not allowed to set ID manually');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param EmailInterface $email
     *
     * @return UserInterface
     */
    public function addEmail(EmailInterface $email): UserInterface
    {
        if (!$this->emails->contains($email)) {
            $email->setUser($this);
            $this->emails->add($email);
        }

        return $this;
    }

    /**
     * @param EmailInterface $email
     *
     * @return UserInterface
     */
    public function removeEmail(EmailInterface $email): UserInterface
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Collection|EmailInterface[]
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @return EmailInterface
     */
    public function getDefaultEmail()
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('default', true));

        return $this->emails->matching($criteria)->current();
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return UserInterface
     */
    public function setPassword(string $password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

}
