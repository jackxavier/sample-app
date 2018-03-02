<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;

/**
 * Class PipStatusSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipStatusSpecification implements SpecificationInterface
{
    /**
     * @param Criteria   $criteria
     * @param BuildEvent|DoctrineBuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event)
    {
        $expr = $criteria->getWhereExpression();

        if (!$expr instanceof Value) {
            throw new \RuntimeException(
                sprintf('Pip `status` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();
        if (empty($value)) {
            return false;
        }

        $rootAlias    = $event->getRootAlias();
        $queryBuilder = $event->getQueryBuilder();

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->eq(sprintf('%s.status', $rootAlias), ':pip_status')
            )
            ->setParameter('pip_status', $value);

        return true;
    }
}
