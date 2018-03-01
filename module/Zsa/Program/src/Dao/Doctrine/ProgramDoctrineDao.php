<?php

namespace Zsa\Program\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Zsa\Program\Dao\ProgramDaoInterface;
use Zsa\Program\Entity\Program;

/**
 * Class ProgramDoctrineDao
 *
 * @package Zsa\Program\Dao\Doctrine
 */
class ProgramDoctrineDao extends AbstractDoctrineDao implements ProgramDaoInterface
{
    /**
     * @param Program $program
     *
     * @return void
     */
    public function save(Program $program)
    {
        $this->objectManager->persist($program);
        $this->objectManager->flush();
    }

    /**
     * @param Program $program
     *
     * @return void
     */
    public function remove(Program $program)
    {
        $this->objectManager->remove($program);
        $this->objectManager->flush();
    }

    /**
     * @param $id
     *
     * @return null|object|Program
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return Program|object|null
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('prgrm');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return array|Program[]
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('prgrm');
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
        $queryBuilder = $this->repository->createQueryBuilder('prgrm');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}