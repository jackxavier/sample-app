<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\Query\Expr\Join;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification as Specification;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Entity\Relation\PipSelfRelation;

/**
 * Class PipSelfRelationSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipSelfRelationSpecification implements SpecificationInterface
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
                sprintf('Pip `self` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();
        if (empty($value)) {
            return false;
        }

        $alias         = $event->getRootAlias();
        $queryBuilder  = $event->getQueryBuilder();
        $entityManager = $queryBuilder->getEntityManager();
        /** @var Pip $pip */
        $pip = $entityManager->getRepository(Pip::class)->find($value);
        if (!$pip) {
            return false;
        }

        if (!in_array(Specification::ALIAS_PIP_RELATION, $event->getAliases())) {
            $queryBuilder->leftJoin(sprintf('%s.relations', $alias), Specification::ALIAS_PIP_RELATION);
            $event->addAlias(Specification::ALIAS_PIP_RELATION);
        }

        if (!in_array(Specification::ALIAS_PIP_SELF_RELATION, $event->getAliases())) {
            $queryBuilder->leftJoin(
                PipSelfRelation::class,
                Specification::ALIAS_PIP_SELF_RELATION,
                Join::WITH,
                sprintf('%s.id = %s.id', Specification::ALIAS_PIP_RELATION, Specification::ALIAS_PIP_SELF_RELATION)
            );
            $event->addAlias(Specification::ALIAS_PIP_SELF_RELATION);
        }

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->eq(sprintf('%s.self', Specification::ALIAS_PIP_SELF_RELATION), ':pip')
            )
            ->setParameter('pip', $pip->getId());

        return true;
    }
}
