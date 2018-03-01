<?php

namespace Zsa\Program\Filter;

use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Service\UserService;
use Zsa\Program\Entity\Program;
use Zsa\Program\Transformer\ProgramTransformer;
use Zend\Filter\FilterInterface;

/**
 * Class ProgramFilter
 *
 * @package Zsa\Program\Filter
 */
class ProgramFilter implements FilterInterface
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * ProgramFilter constructor.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }

    /**
     * @param mixed $value
     *
     * @return \Zsa\Program\Dto\Program\ProgramViewTo|mixed
     */
    public function filter($value)
    {
        if (!$value instanceof Program) {
            return $value;
        }
        /** @var User $user */
        $user = $this->userService->getAuthenticatedUser();

        return ProgramTransformer::toProgramViewTo($user, $value);
    }
}