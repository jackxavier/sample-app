<?php

namespace Axmit\UserCore\Dto;

/**
 * Class UserTo
 *
 * @package Axmit\UserCore\Dto
 */
class UserTo
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserTo
     */
    public function setId(int $id): UserTo
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return UserTo
     */
    public function setName(string $name): UserTo
    {
        $this->name = $name;

        return $this;
    }
}