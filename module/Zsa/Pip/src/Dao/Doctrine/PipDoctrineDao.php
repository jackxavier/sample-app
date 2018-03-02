<?php

namespace Zsa\Pip\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Axmit\UserCore\Entity\User;
use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Entity\Pip;

/**
 * Class PipDoctrineDao
 *
 * @package Zsa\Pip\Dao\Doctrine
 */
class PipDoctrineDao extends AbstractDoctrineDao implements PipDaoInterface
{
    /**
     * @param Pip  $pip
     * @param bool $flush
     *
     * @return bool
     */
    public function tryToSave(Pip $pip, $flush = true)
    {
        try {
            $this->objectManager->persist($pip);

            if ($flush) {
                $this->objectManager->flush();
            }

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param Pip  $pip
     * @param bool $flush
     *
     * @return bool
     */
    public function tryToRemove(Pip $pip, $flush = true)
    {
        try {
            $this->objectManager->remove($pip);

            if ($flush) {
                $this->objectManager->flush();
            }

        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param $id
     *
     * @return Pip|object|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return Pip|null
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pip');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return Pip[]
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pip');
        $queryBuilder->leftJoin('pip.assignees', 'pass');
        $query = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getResult();
    }

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pip');
        $queryBuilder->addSelect(['pass', 'ptags', 'prel'])
                     ->leftJoin('pip.assignees', 'pass')
                     ->leftJoin('pip.pipTags', 'ptags')
                     ->leftJoin('pip.relations', 'prel');

        $query = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }

    /**
     * @param User   $user
     * @param Filter $filter
     *
     * @return Pip[]
     * @internal param User $user
     *
     */
    public function findUserPips(User $user, Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('pip');
        $queryBuilder->leftJoin('pip.assignees', 'pass')
                     ->where($queryBuilder->expr()->eq('pass.user', ':user'))
                     ->setParameters(
                         [
                             'user' => $user->getId(),
                         ]
                     );

        $query = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getResult();
    }
}