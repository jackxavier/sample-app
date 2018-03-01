<?php

namespace Zsa\Pip\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zsa\Pip\Entity\Enum\PipRelationsEnum;

/**
 * Class AbstractPipRelation
 *
 * @ORM\Entity
 * @ORM\Table(name="pip_relations")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="relates", type="string")
 * @ORM\DiscriminatorMap({
 *  "self" = "Zsa\Pip\Entity\Relation\PipSelfRelation",
 *  "project"  = "Zsa\Pip\Entity\Relation\PipProjectRelation",
 *  "program"  = "Zsa\Pip\Entity\Relation\PipProgramRelation",
 * })
 */
abstract class PipRelation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    protected $type = PipRelationsEnum::VALUE_RELATE;

    /**
     * @ORM\ManyToOne(targetEntity="Pip", inversedBy="relations")
     * @ORM\JoinColumn(name="pip", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     *
     * @var Pip
     */
    protected $pip;

    /**
     * AbstractPipRelation constructor.
     *
     * @param Pip  $pip
     * @param null $type
     */
    public function __construct(Pip $pip, $type = null)
    {
        if ($type) {
            $this->setType($type);
        }

        $this->pip = $pip;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return PipRelation
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return PipRelation
     */
    public function setType($type)
    {
        if (!in_array($type, PipRelationsEnum::getSupportedRelations())) {
            throw new \OutOfBoundsException(
                sprintf('Type %s is not supported', $type)
            );
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return Pip
     */
    public function getPip()
    {
        return $this->pip;
    }

    /**
     * @param Pip $master
     *
     * @return PipRelation
     */
    public function setPip($pip)
    {
        $this->pip = $pip;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function getRelated();
}