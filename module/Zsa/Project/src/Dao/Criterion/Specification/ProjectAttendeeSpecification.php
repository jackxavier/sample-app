<?php

namespace Zsa\Project\Dao\Criterion\Specification;

use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;

/**
 * Class ProjectAttendeeSpecification
 *
 * @package Zsa\Project\Dao\Criterion\Specification
 */
class ProjectAttendeeSpecification implements SpecificationInterface
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
                sprintf('Project `user` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();
        if (empty($value)) {
            return false;
        }

        $queryBuilder = $event->getQueryBuilder();
        $rootAlias    = $event->getRootAlias();

        $queryBuilder->leftJoin(sprintf('%s.attendees', $rootAlias), 'patt')
                     ->andWhere(
                         $queryBuilder->expr()->orX(
                             $queryBuilder->expr()->eq(sprintf('%s.controller', $rootAlias), ':user'),
                             $queryBuilder->expr()->eq('patt.user', ':user')
                         )
                     )
                     ->setParameter('user', $value);

        return true;
    }

}
