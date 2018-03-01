<?php

namespace Zsa\Project\Dto\Project;

/**
 * Class ProjectViewTo
 *
 * @package Zsa\Project\Dto\Project
 */
class ProjectViewTo extends ProjectEditTo
{
    /**
     * @var \Traversable
     */
    protected $pipCollection = [];

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
     * @return ProjectViewTo
     */
    public function setPipCollection($pipCollection)
    {
        $this->pipCollection = $pipCollection;

        return $this;
    }
}