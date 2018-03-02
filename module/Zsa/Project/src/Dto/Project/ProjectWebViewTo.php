<?php

namespace Zsa\Project\Dto\Project;

/**
 * Class ProjectWebViewTo
 *
 * @package Zsa\Project\Dto\Project
 */
class ProjectWebViewTo extends ProjectViewTo
{
    /**
     * @var mixed
     */
    protected $createdBy = null;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var mixed
     */
    protected $updatedBy = null;

    /**
     * @var mixed
     */
    protected $updatedOn = null;

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
     * @return ProjectWebViewTo
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

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
     * @return ProjectWebViewTo
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

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
     * @return ProjectWebViewTo
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param mixed $updatedOn
     *
     * @return ProjectWebViewTo
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }
}
