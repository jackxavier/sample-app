<?php

namespace Zsa\Pip\Dto\PipRelation;

use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Entity\Relation\PipProgramRelation;
use Zsa\Pip\Entity\Relation\PipProjectRelation;
use Zsa\Pip\Entity\Relation\PipProtocolRelation;
use Zsa\Pip\Entity\Relation\PipTaskRelation;
use Zsa\Program\Entity\Program;
use Zsa\Project\Entity\Project;
use Zsa\Protocol\Entity\Protocol;
use Zsa\Task\Entity\Task;

/**
 * Class PipRelationTo
 *
 * @package Zsa\Pip\Dto\PipRelation
 */
class PipRelationTo
{
    /**
     * @var mixed
     */
    protected $pip;

    /**
     * @var mixed
     */
    protected $relationType;

    /**
     * @return mixed
     */
    public function getPip()
    {
        return $this->pip;
    }

    /**
     * @param mixed $pip
     *
     * @return PipRelationTo
     */
    public function setPip($pip)
    {
        $this->pip = $pip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * @param mixed $relationType
     *
     * @return PipRelationTo
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * @param PipContainerInterface $pipContainer
     *
     * @return PipProgramRelation|PipProjectRelation|PipProtocolRelation|PipTaskRelation|null
     */
    public function mutateToEntity(PipContainerInterface $pipContainer)
    {
        switch (true) {
            case $pipContainer instanceOf Program :
                return new PipProgramRelation($pipContainer, $this->getPip(), $this->getRelationType());
                break;

            case $pipContainer instanceOf Protocol :
                return new PipProtocolRelation($pipContainer, $this->getPip(), $this->getRelationType());
                break;

            case $pipContainer instanceOf Project :
                return new PipProjectRelation($pipContainer, $this->getPip(), $this->getRelationType());
                break;

            case $pipContainer instanceOf Task :
                return new PipTaskRelation($pipContainer, $this->getPip(), $this->getRelationType());
                break;
        }

        return null;
    }
}