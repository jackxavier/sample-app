<?php

namespace Zsa\Program\Dto\Program;

/**
 * Class ProgramEditTo
 *
 * @package Zsa\Program\Dto\Program
 */
class ProgramEditTo extends ProgramTo
{
    /**
     * @var mixed
     */
    protected $projects = [];

    /**
     * @var mixed
     */
    protected $protocols = [];

    /**
     * @return mixed
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param mixed $projects
     *
     * @return ProgramEditTo
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProtocols()
    {
        return $this->protocols;
    }

    /**
     * @param mixed $protocols
     *
     * @return ProgramEditTo
     */
    public function setProtocols($protocols)
    {
        $this->protocols = $protocols;

        return $this;
    }
}