<?php

namespace Zsa\Pip\Dao;

use Axmit\Dao\Criterion\Filter;
use Zsa\Pip\Entity\PipHistoryRegistry;

/**
 * Interface PipHistoryRegistryDaoInterface
 *
 * @package Zsa\Pip\Dao
 */
interface PipHistoryRegistryDaoInterface
{
    /**
     * @param PipHistoryRegistry $pipRecord
     *
     * @return bool
     */
    public function tryToSave(PipHistoryRegistry $pipRecord);

    /**
     * @param PipHistoryRegistry $pipRecord
     *
     * @return bool
     */
    public function tryToRemove(PipHistoryRegistry $pipRecord);

    /**
     * @param $id
     *
     * @return PipHistoryRegistry|object|null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return PipHistoryRegistry|null
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return PipHistoryRegistry[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);
}