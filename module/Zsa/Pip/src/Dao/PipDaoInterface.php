<?php

namespace Zsa\Pip\Dao;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Entity\User;
use Zsa\Pip\Entity\Pip;

/**
 * Class PipDaoInterface
 *
 * @package Zsa\Pip\Dao
 */
interface PipDaoInterface
{
    /**
     * @param Pip  $pip
     * @param bool $flush
     *
     * @return bool
     */
    public function tryToSave(Pip $pip, $flush = true);

    /**
     * @param Pip  $pip
     * @param bool $flush
     *
     * @return bool
     */
    public function tryToRemove(Pip $pip, $flush = true);

    /**
     * @param $id
     *
     * @return Pip|object|null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return Pip|null
     */
    public function findOneByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return Pip[]
     */
    public function findByFilter(Filter $filter);

    /**
     * @param Filter $filter
     *
     * @return \Zend\Paginator\Paginator
     */
    public function findPaginatedByFilter(Filter $filter);

    /**
     * @param User   $user
     * @param Filter $filter
     *
     * @return mixed
     */
    public function findUserPips(User $user, Filter $filter);
}