<?php

namespace Zsa\Pip\Entity\Relation;

use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Program\Entity\Program;

/**
 * Class PipProgramRelations
 *
 * @ORM\Entity
 * @ORM\Table(name="pip_program_relations")
 */
class PipProgramRelation extends PipRelation
{
    const RELATED_ENTITY_TYPE_NAME = 'program';

    /**
     * @ORM\ManyToOne(targetEntity="Zsa\Program\Entity\Program", inversedBy="programPips")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @var Program
     */
    protected $program;

    /**
     * KpiProgram constructor.
     *
     * @param Program $program
     */
    public function __construct(Program $program, Pip $pip, $type = null)
    {
        $this->program = $program;

        parent::__construct($pip, $type);
    }

    /**
     * @return Program
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param Program $program
     *
     * @return $this
     */
    public function setProgram(Program $program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * @return Program
     */
    public function getRelated()
    {
        return $this->getProgram();
    }
}
