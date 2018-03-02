<?php

namespace Zsa\Program\Dao\Hydrator;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Criterion\Hydrator\DefaultFilterHydrator;
use Axmit\UserCore\Dao\UserDaoInterface;

/**
 * Class ProgramFilteringHydrator
 *
 * @package Zsa\Program\Dao\Hydrator
 */
class ProgramFilteringHydrator extends DefaultFilterHydrator
{
    /**
     * @var UserDaoInterface
     */
    protected $userDao;

    /**
     * ProgramFilteringHydrator constructor.
     *
     * @param UserDaoInterface $userDao
     */
    public function __construct(UserDaoInterface $userDao)
    {
        $this->userDao = $userDao;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateDefault($name, $value, Filter $filter)
    {
        switch ($name) {
            case 'title':
                $filter->andConstraint($name)->contains($value);
                break;
            case 'controller':
                $user = $this->userDao->find((int)$value);

                if ($user) {
                    $filter->andConstraint('controller')->eq($user->getId());
                }

                break;
            default:
                parent::hydrateDefault($name, $value, $filter);
                break;
        }
    }
}