<?php

namespace Zsa\Pip\Entity\Relation;

use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Project\Entity\Project;

/**
 * Class PipProjectRelations
 *
 * @ORM\Entity
 * @ORM\Table(name="pip_project_relations")
 */
class PipProjectRelation extends PipRelation
{
    const RELATED_ENTITY_TYPE_NAME = 'project';

    /**
     * @ORM\ManyToOne(targetEntity="Zsa\Project\Entity\Project", inversedBy="projectPips")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @var Project
     */
    protected $project;

    /**
     * PipProjectRelation constructor.
     *
     * @param Project $project
     * @param Pip     $pip
     * @param null    $type
     */
    public function __construct(Project $project, Pip $pip, $type = null)
    {
        $this->project = $project;

        parent::__construct($pip, $type);
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Project
     */
    public function getRelated()
    {
        return $this->getProject();
    }
}
