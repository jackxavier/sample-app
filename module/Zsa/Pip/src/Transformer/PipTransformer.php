<?php

namespace Zsa\Pip\Transformer;

use Axmit\UserCore\Transformer\UserTransformer;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zsa\Pip\Dto\Pip\PipEditTo;
use Zsa\Pip\Dto\Pip\PipTo;
use Zsa\Pip\Dto\Pip\PipViewTo;
use Zsa\Pip\Dto\Pip\PipWebViewTo;
use Zsa\Pip\Dto\PipRelation\PipRelationCollection;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Dto\PipRelation\ReversePipRelationTo;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\PipRelation;
use Zsa\Pip\Entity\Relation\PipProgramRelation;
use Zsa\Pip\Entity\Relation\PipProjectRelation;
use Zsa\Pip\Entity\Relation\PipSelfRelation;
use Zsa\Program\Transformer\ProgramTransformer;
use Zsa\Project\Transformer\ProjectTransformer;

/**
 * Class PipTransformer
 *
 * @package Zsa\Pip\Transformer
 */
class PipTransformer
{
    /**
     * @param Pip            $pip
     * @param PipEditTo|null $editTo
     *
     * @return PipTo|PipEditTo|PipViewTo
     */
    public static function toPipEditTo(Pip $pip, PipEditTo $editTo = null)
    {
        if (!$editTo) {
            $editTo = new PipEditTo();
        }

        if (!empty($pip->getBody())) {
            $editTo->setBody($pip->getBody());
        }

        if ($pip->getStatus()) {
            $editTo->setStatus($pip->getStatus());
        }

        if ($pip->getType()) {
            $editTo->setType($pip->getType());
        }

        if ($pip->hasAssignees()) {
            $solver = $pip->getSolver();

            if ($solver) {
                $pipAssigneeTo = UserTransformer::toUserTo($solver->getUser());
                $editTo->setAssignee($pipAssigneeTo);
            }
        }

        if ($pip->getParentPip()) {
            $editTo->setParent(self::toPipTo($pip->getParentPip()));
        }

        return self::toPipTo($pip, $editTo);
    }

    /**
     * @param Pip            $pip
     * @param PipViewTo|null $pipViewTo
     *
     * @return PipEditTo|PipViewTo
     */
    public static function toPipViewTo(Pip $pip, PipViewTo $pipViewTo = null)
    {
        if (!$pipViewTo) {
            $pipViewTo = new PipViewTo();
        }

        if ($pip->getCreatedBy()) {
            $pipViewTo->setCreatedBy(UserTransformer::toUserTo($pip->getCreatedBy()));
        }

        $pipViewTo->setCreatedOn($pip->getCreatedOn());

        if ($pip->hasPips()) {

            $pips = $pip->getPips()->map(
                function (PipRelation $pip) {
                    return PipTransformer::toPipRelationTo($pip);
                }
            );

            $pipToCollection = new PipRelationCollection(new ArrayAdapter($pips->toArray()));

            $pipViewTo->setRelatedPips($pipToCollection);
        }

        $relationsTo = [];

        foreach ($pip->getAllRelations() as $relation) {
            $relationsTo[] = self::toReversePipRelationTo($relation);
        }

        $pipViewTo->setRelations(new PipRelationCollection(new ArrayAdapter($relationsTo)));

        return self::toPipEditTo($pip, $pipViewTo);
    }

    /**
     * @param Pip               $pip
     * @param PipWebViewTo|null $pipWebViewTo
     *
     * @return PipWebViewTo|PipViewTo
     */
    public static function toPipWebViewTo(Pip $pip, PipWebViewTo $pipWebViewTo = null)
    {
        if (!$pipWebViewTo) {
            $pipWebViewTo = new PipWebViewTo();
        }

        if ($pip->getUpdatedBy()) {
            $pipWebViewTo->setUpdatedBy($pip->getUpdatedBy()->getId());
        }

        if ($pip->getUpdatedOn()) {
            $pipWebViewTo->setUpdatedOn($pip->getUpdatedOn());
        }

        return self::toPipViewTo($pip, $pipWebViewTo);
    }

    /**
     * @param Pip        $pip
     * @param PipTo|null $pipTo
     *
     * @return PipTo|PipEditTo|PipViewTo
     */
    public static function toPipTo(Pip $pip, PipTo $pipTo = null)
    {
        if (!$pipTo) {
            $pipTo = new PipTo();
        }
        $pipTo->setId($pip->getId())
              ->setTitle($pip->getTitle());

        return $pipTo;
    }

    /**
     * @param PipRelation        $relation
     * @param PipRelationTo|null $relationTo
     *
     * @return PipRelationTo
     */
    public static function toPipRelationTo(PipRelation $relation, PipRelationTo $relationTo = null)
    {
        if (!$relationTo) {
            $relationTo = new PipRelationTo();
        }

        $relationTo->setPip(self::toPipTo($relation->getPip()))
                   ->setRelationType($relation->getType());

        return $relationTo;
    }

    /**
     * @param PipRelation               $relation
     * @param ReversePipRelationTo|null $relationTo
     *
     * @return ReversePipRelationTo
     */
    public static function toReversePipRelationTo(PipRelation $relation, ReversePipRelationTo $relationTo = null)
    {
        if (!$relationTo) {
            $relationTo = new ReversePipRelationTo();
        }

        switch (true) {
            case ($relation instanceOf PipProgramRelation) :
                $relationTo->setRelatedEntity(ProgramTransformer::toProgramTo($relation->getProgram()));
                $relationTo->setRelatedEntityType(PipProgramRelation::RELATED_ENTITY_TYPE_NAME);
                break;
            case ($relation instanceOf PipProjectRelation) :
                $relationTo->setRelatedEntity(ProjectTransformer::toProjectTo($relation->getProject()));
                $relationTo->setRelatedEntityType(PipProjectRelation::RELATED_ENTITY_TYPE_NAME);
                break;
            case ($relation instanceOf PipSelfRelation) :
                $relationTo->setRelatedEntity(self::toPipTo($relation->getRelated()));
                $relationTo->setRelatedEntityType(PipSelfRelation::RELATED_ENTITY_TYPE_NAME);
                break;
        }

        $relationTo->setRelationType($relation->getType());

        return $relationTo;
    }


}