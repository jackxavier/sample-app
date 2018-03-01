<?php

namespace Zsa\Pip\Dao;

use Axmit\Dao\Criterion\Filter;
use Zsa\Pip\Entity\PipAssignee;

/**
 * Interface PipAssigneeDaoInterface
 *
 * @package Zsa\Pip\Dao
 */
interface PipAssigneeDaoInterface
{
    /**
     * @param PipAssignee $pipAssignee
     *
     * @return bool
     */
    public function tryToSave(PipAssignee $pipAssignee);

    /**
     * @param PipAssignee $pipAssignee
     *
     * @return bool
     */
    public function tryToRemove(PipAssignee $pipAssignee);

    /**
     * @param $id
     *
     * @return PipAssignee|object|null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return PipAssignee|null
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return PipAssignee[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);
}