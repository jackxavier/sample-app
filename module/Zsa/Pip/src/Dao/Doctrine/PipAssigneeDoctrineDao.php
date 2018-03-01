<?php

namespace Zsa\Pip\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Zsa\Pip\Dao\PipAssigneeDaoInterface;
use Zsa\Pip\Entity\PipAssignee;

/**
 * Class PipAssigneeDoctrineDao
 *
 * @package Zsa\Pip\Dao\Doctrine
 */
class PipAssigneeDoctrineDao extends AbstractDoctrineDao implements PipAssigneeDaoInterface
{
    /**
     * @param PipAssignee $pipAssignee
     *
     * @return bool
     */
    public function tryToSave(PipAssignee $pipAssignee)
    {
        try {
            $this->objectManager->persist($pipAssignee);
            $this->objectManager->flush();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param PipAssignee $pipAssignee
     *
     * @return bool
     */
    public function tryToRemove(PipAssignee $pipAssignee)
    {
        try {
            $this->objectManager->remove($pipAssignee);
            $this->objectManager->flush();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param $id
     *
     * @return PipAssignee|object|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return PipAssignee|null
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pipass');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return PipAssignee[]
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pipass');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getResult();
    }

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pipass');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}