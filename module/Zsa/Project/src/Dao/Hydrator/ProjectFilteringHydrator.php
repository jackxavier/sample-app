<?php

namespace Zsa\Project\Dao\Hydrator;

use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Criterion\Hydrator\DefaultFilterHydrator;
use Axmit\UserCore\Dao\UserDaoInterface;
use Zsa\Project\Dao\Criterion\ProjectSpecifications;

/**
 * Class ProjectFilteringHydrator
 *
 * @package Zsa\Project\Dao\Hydrator
 */
class ProjectFilteringHydrator extends DefaultFilterHydrator
{
    /**
     * @var UserDaoInterface
     */
    protected $userDao;

    /**
     * ProjectFilteringHydrator constructor.
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
            case 'hasProgram':
                if ($value) {
                    $filter->andConstraint('prgrm.id')->neq(null);
                } else {
                    $filter->andConstraint('prgrm.id')->eq(null);
                }

                break;
            case 'controller':
                $user = $this->userDao->find((int)$value);

                if ($user) {
                    $filter->andConstraint('controller')->eq($user->getId());
                }

                break;
            case 'attendee':
                $filter->andConstraint(ProjectSpecifications::PROJECT_ATTENDEE)->value($value);

                break;
            default:
                parent::hydrateDefault($name, $value, $filter);
                break;
        }
    }
}