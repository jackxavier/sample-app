<?php

namespace Axmit\UserCore\Entity;

use DateTime;

/**
 * Interface EmailInterface
 *
 * @package Axmit\UserCore\Entity
 */
interface EmailInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface;

    /**
     * @param UserInterface $user
     *
     * @return EmailInterface
     */
    public function setUser(UserInterface $user): EmailInterface;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     *
     * @return EmailInterface
     */
    public function setEmail(string $email): EmailInterface;

    /**
     * @return boolean
     */
    public function isDefault(): bool;

    /**
     * @param boolean $default
     *
     * @return EmailInterface
     */
    public function setDefault(bool $default = true): EmailInterface;

    /**
     * @return DateTime
     */
    public function getCreatedOn(): DateTime;
}