<?php

namespace Zsa\Project\Dto\Project;

/**
 * Class ProjectEditTo
 *
 * @package Zsa\Project\Dto\Project
 */
class ProjectEditTo extends ProjectTo
{
    /**
     * @var mixed
     */
    protected $program = null;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var mixed
     */
    protected $attendees = [];

    /**
     * @return mixed
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param mixed $program
     *
     * @return ProjectEditTo
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ProjectEditTo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttendees()
    {
        return $this->attendees;
    }

    /**
     * @param mixed $attendees
     *
     * @return ProjectEditTo
     */
    public function setAttendees($attendees)
    {
        $this->attendees = $attendees;

        return $this;
    }

}