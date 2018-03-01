<?php

namespace Axmit\Dao\Criterion\Specification\Doctrine;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Criterion\Specification\SpecificationInterface;
use Axmit\Dao\Criterion\Specification\SpecificationManager;
use RuntimeException;

/**
 * Class DoctrineSpecificationManager
 *
 * @package Axmit\Dao\Criterion\Specification\Doctrine
 * @author  ma4eto <eddiespb@gmail.com>
 */
class DoctrineSpecificationManager implements SpecificationManager
{

    /**
     * @var SpecificationInterface[]
     */
    protected $specifications = [];

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param string                 $name
     * @param SpecificationInterface $spec
     *
     * @return void
     */
    public function addSpecification($name, SpecificationInterface $spec)
    {
        $this->specifications[$name] = $spec;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasSpecification($name)
    {
        return array_key_exists($name, $this->specifications);
    }

    /**
     * @param string $name
     *
     * @return SpecificationInterface
     */
    public function getSpecification($name)
    {
        return $this->specifications[$name];
    }

    /**
     * @param Filter $filter
     *
     * @return void
     */
    public function apply(Filter $filter)
    {
        if (!isset($this->queryBuilder)) {
            throw new RuntimeException('QueryBuilder is not defined');
        }

        $event       = new DoctrineBuildEvent($this->queryBuilder);
        $rootAliases = $this->queryBuilder->getRootAliases();
        $allAliases  = $this->queryBuilder->getAllAliases();

        $event->setRootAlias($rootAliases[0]);
        foreach ($allAliases as $alias) {
            $event->addAlias($alias);
        }

        foreach ($filter as $name => $criteria) {
            $result = $this->applyCriteria($name, $criteria, $event);

            if (true === $result) {
                $event->addApplied($name, $criteria);
            }
        }
    }

    /**
     * @param string             $name
     * @param Criteria           $criteria
     * @param DoctrineBuildEvent $event
     *
     * @return bool
     * @throws \Doctrine\ORM\Query\QueryException
     */
    protected function applyCriteria($name, Criteria $criteria, DoctrineBuildEvent $event)
    {
        if ($this->hasSpecification($name)) {
            return $this->getSpecification($name)->apply($criteria, $event);
        }

        $this->queryBuilder->addCriteria($criteria);

        return true;
    }
}
