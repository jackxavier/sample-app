<?php

namespace Zsa\Project\Dao;

use Axmit\Dao\Criterion\Filter;
use Zsa\Project\Entity\Project;

/**
 * Interface ProjectDaoInterface
 *
 * @package Zsa\Project\Dao
 */
interface ProjectDaoInterface
{
    /**
     * @param Project $project
     *
     * @return void
     */
    public function save(Project $project);

    /**
     * @param Project $project
     *
     * @return void
     */
    public function remove(Project $project);

    /**
     * @param $id
     *
     * @return Project|object|null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return Project|object|null
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return Project[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);
}