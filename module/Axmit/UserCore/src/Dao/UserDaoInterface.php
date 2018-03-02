<?php

namespace Axmit\UserCore\Dao;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Entity\UserInterface;

/**
 * Interface UserDaoInterface
 *
 * @package Axmit\UserCore\Dao
 */
interface UserDaoInterface
{
    /**
     * @param $id
     *
     * @return UserInterface | null
     */
    public function find($id);

    /**
     * @param Filter $filter
     *
     * @return mixed
     */
    public function findByFilter(Filter $filter);

}