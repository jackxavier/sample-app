<?php

namespace Axmit\Dao\Criterion\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Axmit\Dao\Criterion\Specification\BuildEvent;

/**
 * Class DoctrineBuildEvent
 *
 * @package Axmit\Dao\Criterion\Specification\Doctrine
 * @author  ma4eto <eddiespb@gmail.com>
 */
class DoctrineBuildEvent extends BuildEvent
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var string
     */
    protected $rootAlias;

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * DoctrineBuildEvent constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @return string
     */
    public function getRootAlias()
    {
        return $this->rootAlias;
    }

    /**
     * @param string $rootAlias
     *
     * @return DoctrineBuildEvent
     */
    public function setRootAlias($rootAlias)
    {
        $this->rootAlias = $rootAlias;

        return $this;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @param string $alias
     *
     * @return DoctrineBuildEvent
     */
    public function addAlias($alias)
    {
        $this->aliases = array_unique(array_merge($this->aliases, [$alias]));

        return $this;
    }

}
