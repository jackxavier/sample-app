<?php

namespace Zsa\Pip\Dto\PipAssignee;

/**
 * Class PipAssigneeTo
 *
 * @package Zsa\Pip\Dto\PipAssignee
 */
class PipAssigneeTo
{
    /**
     * @var mixed
     */
    protected $assignee;

    /**
     * @var string
     */
    protected $status;

    /**
     * @return mixed
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param int $assignee
     *
     * @return PipAssigneeTo
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;

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
     * @return PipAssigneeTo
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

}