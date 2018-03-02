<?php


namespace Axmit\Util\Doctrine\Entity;

/**
 * Trait TimestampTrait
 *
 * @package Axmit\Util\Doctrine\Entity
 */
trait TimestampTrait
{
    /**
     * @ORM\Column (name = "created_on", type = "datetime")
     *
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @ORM\Column (name = "updated_on", type = "datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     *
     * @return mixed
     */
    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn(): \DateTime
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime $updatedOn
     *
     * @return mixed
     */
    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }


}