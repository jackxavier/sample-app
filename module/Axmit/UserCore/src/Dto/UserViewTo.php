<?php

namespace Axmit\UserCore\Dto;

/**
 * Class UserViewTo
 *
 * @package Axmit\UserCore\Dto
 */
class UserViewTo extends UserTo
{
    /**
     * @var string
     */
    protected $defaultEmail = '';

    /**
     * @return string
     */
    public function getDefaultEmail(): string
    {
        return $this->defaultEmail;
    }

    /**
     * @param string $defaultEmail
     *
     * @return UserViewTo
     */
    public function setDefaultEmail(string $defaultEmail): UserViewTo
    {
        $this->defaultEmail = $defaultEmail;

        return $this;
    }
}