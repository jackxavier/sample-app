<?php

namespace Zsa\Project\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Zsa\Project\Dao\ProjectDaoInterface;
use Zsa\Project\Entity\Project;

/**
 * Class ProjectDoctrineDao
 *
 * @package Zsa\Project\Dao\Doctrine
 */
class ProjectDoctrineDao extends AbstractDoctrineDao implements ProjectDaoInterface
{
    /**
     * @param Project $project
     *
     * @return void
     */
    public function save(Project $project)
    {
        $this->objectManager->persist($project);
        $this->objectManager->flush();
    }

    /**
     * @param Project $project
     *
     * @return void
     */
    public function remove(Project $project)
    {
        $this->objectManager->remove($project);
        $this->objectManager->flush($project);
    }

    /**
     * @param $id
     *
     * @return Project|object|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return Project|object|null
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('prj');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $query->getOneOrNullResult();
    }

    /**
     * @param Filter $filter
     *
     * @return Project[]
     */
    public function findByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('prj');
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
        $queryBuilder = $this->repository->createQueryBuilder('prj')
                                         ->leftJoin('prj.programs', 'prgrm');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}