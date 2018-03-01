<?php

namespace Zsa\Pip\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Zsa\Pip\Dao\PipHistoryRegistryDaoInterface;
use Zsa\Pip\Entity\PipHistoryRegistry;

/**
 * Class PipHistoryRegistryDoctrineDao
 *
 * @package Zsa\Pip\Dao\Doctrine
 */
class PipHistoryRegistryDoctrineDao extends AbstractDoctrineDao implements PipHistoryRegistryDaoInterface
{
    /**
     * @param PipHistoryRegistry $pipRecord
     *
     * @return bool
     */
    public function tryToSave(PipHistoryRegistry $pipRecord)
    {
        try {
            $this->objectManager->persist($pipRecord);
            $this->objectManager->flush();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param PipHistoryRegistry $pipRecord
     *
     * @return bool
     */
    public function tryToRemove(PipHistoryRegistry $pipRecord)
    {
        try {
            $this->objectManager->remove($pipRecord);
            $this->objectManager->flush();

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param $id
     *
     * @return PipHistoryRegistry|object|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return PipHistoryRegistry|null
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('piphrg');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return PipHistoryRegistry[]
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('piphrg');
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
        $queryBuilder = $this->repository->createQueryBuilder('piphrg');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}