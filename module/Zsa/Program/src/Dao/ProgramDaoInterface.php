<?php

namespace Zsa\Program\Dao;

use Axmit\Dao\Criterion\Filter;
use Zsa\Program\Entity\Program;

/**
 * Interface ProgramDaoInterface
 *
 * @package Zsa\Program\Dao
 */
interface ProgramDaoInterface
{
    /**
     * @param Program $program
     *
     * @return void
     */
    public function save(Program $program);

    /**
     * @param Program $program
     *
     * @return void
     */
    public function remove(Program $program);

    /**
     * @param $id
     *
     * @return Program|object|null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return Program|object|null
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return Program[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);
}