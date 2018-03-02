<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;

/**
 * Class PipIgnoreStatusSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipIgnoreStatusSpecification implements SpecificationInterface
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
                sprintf('Pip `ignoreStatus` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();

        if (empty($value)) {
            return false;
        }

        $rootAlias = $event->getRootAlias();
        $queryBuilder  = $event->getQueryBuilder();

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->neq(sprintf('%s.status', $rootAlias), ':ignoreStatus')
            )
            ->setParameter('ignoreStatus', $value);

        return true;
    }
}
