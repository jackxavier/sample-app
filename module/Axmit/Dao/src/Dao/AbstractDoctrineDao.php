<?php

namespace Axmit\Dao\Dao;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;
use Axmit\Dao\Criterion\Specification\SpecificationManager;
use Zend\Paginator\Paginator;

/**
 * Class AbstractDoctrineDao
 *
 * @package Axmit\Dao\Dao
 * @author  ma4eto <eddiespb@gmail.com>
 */
abstract class AbstractDoctrineDao
{
    /**
     * @var ObjectManager|EntityManager
     */
    protected $objectManager;

    /**
     * @var ObjectRepository|EntityRepository
     */
    protected $repository;

    /**
     * @var SpecificationManager
     */
    protected $specifications;

    /**
     * AbstractDoctrineDao constructor.
     *
     * @param ObjectManager        $objectManager
     * @param string               $entityClass FQCN Entity class name
     * @param SpecificationManager $specifications
     */
    public function __construct(ObjectManager $objectManager, $entityClass, SpecificationManager $specifications)
    {
        $this->objectManager  = $objectManager;
        $this->repository     = $objectManager->getRepository($entityClass);
        $this->specifications = $specifications;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Filter       $filter
     *
     * @return Query
     */
    protected function getFilteredQuery(QueryBuilder $queryBuilder, Filter $filter)
    {
        /** @var DoctrineSpecificationManager $specifications */
        $specifications = $this->specifications;
        $specifications->setQueryBuilder($queryBuilder);
        $specifications->apply($filter);

        return $queryBuilder->getQuery();
    }

    /**
     * @param Query $query
     * @param int   $limit
     * @param int   $offset
     *
     * @return Paginator
     */
    protected function getPaginator(Query $query, $limit, $offset)
    {
        $adapter   = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);

        $offset = ($offset < 0) ? 0 : $offset;
        $limit  = empty($limit) ? $paginator->getDefaultItemCountPerPage() : $limit;

        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber(floor($offset / $limit) + 1);

        return $paginator;
    }
}
