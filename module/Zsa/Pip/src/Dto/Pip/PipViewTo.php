<?php

namespace Zsa\Pip\Dto\Pip;

/**
 * Class PipViewTo
 *
 * @package Zsa\Pip\Dto\Pip
 */
class PipViewTo extends PipEditTo
{
    /**
     * @var mixed
     */
    protected $createdOn;

    /**
     * @var mixed
     */
    protected $createdBy;

    /**
     * @var \Traversable
     */
    protected $relatedPips = [];

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     *
     * @return PipViewTo
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

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
     * @return PipViewTo
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return \Traversable
     */
    public function getRelatedPips()
    {
        return $this->relatedPips;
    }

    /**
     * @param \Traversable $relatedPips
     *
     * @return PipViewTo
     */
    public function setRelatedPips($relatedPips)
    {
        $this->relatedPips = $relatedPips;

        return $this;
    }
}