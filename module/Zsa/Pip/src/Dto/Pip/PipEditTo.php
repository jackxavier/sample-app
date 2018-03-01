<?php

namespace Zsa\Pip\Dto\Pip;

/**
 * Class PipEditTo
 *
 * @package Zsa\Pip\Dto\Pip
 */
class PipEditTo extends PipTo
{
    /**
     * @var mixed
     */
    protected $priority = null;

    /**
     * @var mixed
     */
    protected $body = null;

    /**
     * @var mixed
     */
    protected $assignee = null;

    /**
     * @var mixed
     */
    protected $status = null;

    /**
     * @var mixed
     */
    protected $parent = null;

    /**
     * @var mixed
     */
    protected $relations = null;

    /**
     * @var mixed
     */
    protected $tags = null;

    /**
     * @var null
     */
    protected $type = null;

    /**
     * @var null
     */
    protected $problems = null;

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     *
     * @return PipEditTo
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     *
     * @return PipEditTo
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param mixed $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param mixed $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     *
     * @return PipEditTo
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     *
     * @return PipEditTo
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return null
     */
    public function getProblems()
    {
        return $this->problems;
    }

    /**
     * @param null $problems
     *
     * @return PipEditTo
     */
    public function setProblems($problems = null)
    {
        $this->problems = $problems;

        return $this;
    }
}