<?php

namespace Zsa\Pip\Entity\Relation;

use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;

/**
 * Class PipProgramRelations
 *
 * @ORM\Entity
 * @ORM\Table(name="pip_self_relations")
 */
class PipSelfRelation extends PipRelation
{
    const RELATED_ENTITY_TYPE_NAME = 'pip';

    /**
     * @ORM\ManyToOne(targetEntity="Zsa\Pip\Entity\Pip", inversedBy="relatedPips")
     * @ORM\JoinColumn(name="related_pip_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *
     * @var Pip
     */
    protected $self;

    /**
     * PipSelfRelation constructor.
     *
     * @param Pip  $self
     * @param Pip  $pip
     * @param null $type
     */
    public function __construct(Pip $self, Pip $pip, $type = null)
    {
        $this->self = $self;

        parent::__construct($pip, $type);
    }

    /**
     * @return Pip
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @param Pip $self
     *
     * @return PipSelfRelation
     */
    public function setSelf($self)
    {
        $this->self = $self;

        return $this;
    }

    /**
     * @return Pip
     */
    public function getRelated()
    {
        return $this->getSelf();
    }
}
