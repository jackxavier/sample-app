<?php

namespace Zsa\Pip\Dto\Pip;

class PipWebViewTo extends PipViewTo
{
    /**
     * @var mixed
     */
    protected $updatedOn;

    /**
     * @var mixed
     */
    protected $updatedBy;

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
     * @return PipWebViewTo
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

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
     * @return PipWebViewTo
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
