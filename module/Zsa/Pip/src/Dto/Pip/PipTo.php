<?php

namespace Zsa\Pip\Dto\Pip;

/**
 * Class PipTo
 *
 * @package Zsa\Pip\Dto\Pip
 */
class PipTo
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return PipTo
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return PipTo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }


}