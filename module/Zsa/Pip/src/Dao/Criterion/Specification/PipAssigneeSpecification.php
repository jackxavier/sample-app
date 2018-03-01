<?php

namespace Zsa\Pip\Dao\Criterion\Specification;

use Axmit\UserCore\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;
use Axmit\Dao\Criterion\Specification\BuildEvent;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineBuildEvent;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Zsa\Pip\Entity\PipAssignee;

/**
 * Class PipAssigneeSpecification
 *
 * @package Zsa\Pip\Dao\Criterion\Specification
 */
class PipAssigneeSpecification implements SpecificationInterface
{
    const VALUE_OWNER_PARAM      = 'owner';
    const VALUE_CONTROLLER_PARAM = 'controller';
    const VALUE_SOLVER_PARAM     = 'solver';

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
                sprintf('Pip `assignee` specification supports only %s expression', Value::class)
            );
        }

        $value = $expr->getValue();

        if (empty($value) && !is_array($value)) {
            return false;
        }

        $queryBuilder       = $event->getQueryBuilder();
        $entityManager      = $queryBuilder->getEntityManager();
        $userRepository = $entityManager->getRepository(User::class);

        /** @var User|null $owner */
        $owner = !isset($value[self::VALUE_OWNER_PARAM])
            ? null
            : $userRepository->find(
                $value[self::VALUE_OWNER_PARAM]
            );
        /** @var User|null $controller */
        $controller = !isset($value[self::VALUE_CONTROLLER_PARAM])
            ? null
            : $userRepository->find(
                $value[self::VALUE_CONTROLLER_PARAM]
            );
        /** @var User|null $solver */
        $solver = !isset($value[self::VALUE_SOLVER_PARAM])
            ? null
            : $userRepository->find(
                $value[self::VALUE_SOLVER_PARAM]
            );

        if (!$owner && !$controller && !$solver) {
            return false;
        }

        $expressionAggregate = $queryBuilder->expr()->orX();

        if ($owner) {
            $ownerExpr = $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('pass.user', ':owner'),
                $queryBuilder->expr()->eq('pass.status', ':status_owner')
            );
            $expressionAggregate->add($ownerExpr);
            $queryBuilder->setParameter('owner', $owner->getId())
                         ->setParameter('status_owner', PipAssignee::VALUE_STATUS_OWNER);
        }

        if ($controller) {
            $controllerExpr = $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('pass.user', ':controller'),
                $queryBuilder->expr()->eq('pass.status', ':status_controller')
            );
            $expressionAggregate->add($controllerExpr);
            $queryBuilder->setParameter('controller', $controller->getId())
                         ->setParameter('status_controller', PipAssignee::VALUE_STATUS_CONTROLLER);
        }

        if ($solver) {
            $solverExpr = $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('pass.user', ':solver'),
                $queryBuilder->expr()->eq('pass.status', ':status_solver')
            );
            $expressionAggregate->add($solverExpr);
            $queryBuilder
                ->setParameter('solver', $solver->getId())
                ->setParameter('status_solver', PipAssignee::VALUE_STATUS_SOLVER);
        }

        $queryBuilder->andWhere($expressionAggregate);

        return true;
    }
}
