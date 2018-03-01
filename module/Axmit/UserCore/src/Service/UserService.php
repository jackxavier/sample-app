<?php

namespace Axmit\UserCore\Service;

use Axmit\Dao\Criterion\Filter;
use Axmit\UserCore\Dao\UserDaoInterface;
use Axmit\UserCore\Entity\UserInterface;

/**
 * Class UserService
 *
 * @package Axmit\UserCore\Service
 */
class UserService
{
    /**
     * @var UserDaoInterface
     */
    protected $userDao;

    /**
     * UserService constructor.
     *
     * @param UserDaoInterface $userDao
     */
    public function __construct(UserDaoInterface $userDao)
    {
        $this->userDao = $userDao;
    }

    /**
     * @param $id
     *
     * @return UserInterface|null
     */
    public function findUserById($id)
    {
        return $this->userDao->find($id);
    }

    public function fetchAll(array $params = [])
    {
        $filter = new Filter();
        $filterArray = [];


        return $this->userDao->findByFilter($filter);
    }

}