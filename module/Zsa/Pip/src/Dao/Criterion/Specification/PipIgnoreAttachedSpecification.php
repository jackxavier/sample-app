<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification;

/**
 * Class PipIgnoreAttachedSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipIgnoreAttachedSpecification implements SpecificationInterface
{
    /**
     * @param Criteria                      $criteria
     * @param BuildEvent|DoctrineBuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event)
    {
        $expr = $criteria->getWhereExpression();
        if (!$expr instanceof Value) {
            throw new \RuntimeException(
                sprintf('Pip `ignoreAttached` specification supports only %s expression', Value::class)
            );
        }

        $queryBuilder  = $event->getQueryBuilder();
        $alias = $event->getRootAlias();

        if (!in_array(PipDoctrineSpecification::ALIAS_PIP_RELATION, $event->getAliases())) {
            $queryBuilder->leftJoin(sprintf('%s.relations', $alias), PipDoctrineSpecification::ALIAS_PIP_RELATION);
            $event->addAlias(PipDoctrineSpecification::ALIAS_PIP_RELATION);
        }

        $queryBuilder->andWhere($queryBuilder->expr()->isNull(sprintf('%s.id', PipDoctrineSpecification::ALIAS_PIP_RELATION)));

        return true;
    }
}
