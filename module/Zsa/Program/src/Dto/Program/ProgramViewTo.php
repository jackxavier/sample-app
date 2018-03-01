<?php

namespace Zsa\Program\Dto\Program;

/**
 * Class ProgramViewTo
 *
 * @package Zsa\Program\Dto\Program
 */
class ProgramViewTo extends ProgramEditTo
{
    /**
     * @var \DateTime
     */
    protected $createdOn = null;

    /**
     * @var \DateTime
     */
    protected $updatedOn = null;

    /**
     * @var mixed
     */
    protected $createdBy = null;

    /**
     * @var mixed
     */
    protected $updatedBy = null;

    /**
     * @var bool
     */
    protected $closed;

    /**
     * @var \Traversable
     */
    protected $pipCollection = [];

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     *
     * @return ProgramViewTo
     */
    public function setCreatedOn($createdOn = null)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime $updatedOn
     *
     * @return ProgramViewTo
     */
    public function setUpdatedOn($updatedOn = null)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     *
     * @return ProgramViewTo
     */
    public function setCreatedBy($createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     *
     * @return ProgramViewTo
     */
    public function setUpdatedBy($updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @param bool $closed
     *
     * @return ProgramViewTo
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getPipCollection()
    {
        return $this->pipCollection;
    }

    /**
     * @param \Traversable $pipCollection
     *
     * @return ProgramViewTo
     */
    public function setPipCollection($pipCollection)
    {
        $this->pipCollection = $pipCollection;

        return $this;
    }
}