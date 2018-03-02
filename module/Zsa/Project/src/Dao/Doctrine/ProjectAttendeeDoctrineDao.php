<?php

namespace Zsa\Project\Dao\Doctrine;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Dao\AbstractDoctrineDao;
use Zsa\Project\Dao\ProjectAttendeeDaoInterface;
use Zsa\Project\Entity\Project;
use Zsa\Project\Entity\ProjectAttendee;

/**
 * Class ProjectDoctrineDao
 *
 * @package Zsa\Project\Dao\Doctrine
 */
class ProjectAttendeeDoctrineDao extends AbstractDoctrineDao implements ProjectAttendeeDaoInterface
{
    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return void
     */
    public function save(ProjectAttendee $projectAttendee)
    {
        $this->objectManager->persist($projectAttendee);
        $this->objectManager->flush();
    }

    /**
     * @param ProjectAttendee $projectAttendee
     *
     * @return void
     */
    public function remove(ProjectAttendee $projectAttendee)
    {
        $this->objectManager->remove($projectAttendee);
        $this->objectManager->flush();
    }

    /**
     * @param $id
     *
     * @return Project|null|object
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     *
     * @return Project
     */
    public function findOneByFilter(Filter $filter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('prjat')
                                         ->innerJoin('prjat.project', 'prj')
                                         ->leftJoin('prjat.user', 'prjuser');
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
        $queryBuilder = $this->repository->createQueryBuilder('prjat')
                                         ->innerJoin('prjat.project', 'prt')
                                         ->leftJoin('prjat.user', 'prjuser');
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
        $queryBuilder = $this->repository->createQueryBuilder('prjat')
                                         ->innerJoin('prjat.project', 'prj')
                                         ->leftJoin('prjat.user', 'prjuser');
        $query        = $this->getFilteredQuery($queryBuilder, $filter);

        return $this->getPaginator($query, $filter->getLimit(), $filter->getOffset());
    }
}