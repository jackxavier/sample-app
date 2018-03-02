<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\Query\Expr\Join;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification as Specification;
use Zsa\Pip\Entity\Relation\PipProjectRelation;
use Zsa\Project\Entity\Project;

/**
 * Class PipProjectSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipProjectRelationSpecification implements SpecificationInterface
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
                sprintf('Pip `project` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();
        if (empty($value)) {
            return false;
        }

        $queryBuilder  = $event->getQueryBuilder();
        $entityManager = $queryBuilder->getEntityManager();
        $alias         = $event->getRootAlias();
        /** @var Project $project */
        $project = $entityManager->getRepository(Project::class)->find($value);

        if (!$project) {
            return false;
        }

        if (!in_array(Specification::ALIAS_PIP_RELATION, $event->getAliases())) {
            $queryBuilder->leftJoin(sprintf('%s.relations', $alias), Specification::ALIAS_PIP_RELATION);
            $event->addAlias(Specification::ALIAS_PIP_RELATION);
        }

        if (!in_array(Specification::ALIAS_PIP_PROJECT_RELATION, $event->getAliases())) {
            $queryBuilder->leftJoin(
                PipProjectRelation::class,
                Specification::ALIAS_PIP_PROJECT_RELATION,
                Join::WITH,
                sprintf('%s.id = %s.id', Specification::ALIAS_PIP_RELATION, Specification::ALIAS_PIP_PROJECT_RELATION)
            );
            $event->addAlias(Specification::ALIAS_PIP_PROJECT_RELATION);
        }

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->eq(sprintf('%s.project', Specification::ALIAS_PIP_PROJECT_RELATION), ':project')
            )
            ->setParameter('project', $project->getId());

        return true;
    }
}
