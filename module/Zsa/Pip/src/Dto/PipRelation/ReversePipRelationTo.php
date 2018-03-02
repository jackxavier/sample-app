<?php


namespace Zsa\Pip\Dto\PipRelation;

/**
 * Class ReversePipRelationTo
 *
 * @package Zsa\Pip\Dto\PipRelation
 */
class ReversePipRelationTo
{
    /**
     * @var mixed
     */
    protected $relatedEntity;

    /**
     * @var mixed
     */
    protected $relationType;

    /**
     * @var mixed
     */
    protected $relatedEntityType;

    /**
     * @return mixed
     */
    public function getRelatedEntity()
    {
        return $this->relatedEntity;
    }

    /**
     * @param mixed $relatedEntity
     *
     * @return ReversePipRelationTo
     */
    public function setRelatedEntity($relatedEntity)
    {
        $this->relatedEntity = $relatedEntity;

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
     * @return ReversePipRelationTo
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelatedEntityType()
    {
        return $this->relatedEntityType;
    }

    /**
     * @param mixed $relatedEntityType
     *
     * @return ReversePipRelationTo
     */
    public function setRelatedEntityType($relatedEntityType)
    {
        $this->relatedEntityType = $relatedEntityType;

        return $this;
    }
}