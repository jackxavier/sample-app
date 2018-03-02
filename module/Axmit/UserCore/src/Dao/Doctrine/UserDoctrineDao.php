<?php

namespace Axmit\UserCore\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Axmit\UserCore\Dao\UserDaoInterface;
use Axmit\UserCore\Entity\UserInterface;

/**
 * Class UserDoctrineDao
 *
 * @package Axmit\UserCore\Dao\Doctrine
 */
class UserDoctrineDao extends AbstractDoctrineDao implements UserDaoInterface
{
    /**
     * @param $id
     *
     * @return UserInterface | null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return mixed
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('usr');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}