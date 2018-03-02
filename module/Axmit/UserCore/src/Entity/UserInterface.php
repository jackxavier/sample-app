<?php

namespace Axmit\UserCore\Entity;

/**
 * Interface UserInterface
 *
 * @package Axmit\UserCore\Entity
 */
interface UserInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     *
     * @return UserInterface
     */
    public function setId(int $id): UserInterface;

    /**
     * @param EmailInterface $email
     *
     * @return UserInterface
     */
    public function addEmail(EmailInterface $email): UserInterface;

    /**
     * @param EmailInterface $email
     *
     * @return UserInterface
     */
    public function removeEmail(EmailInterface $email): UserInterface;

    /**
     * @return EmailInterface[]
     */
    public function getEmails();

    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword(): string;

    /**
     * @param string $password
     *
     * @return mixed
     */
    public function setPassword(string $password): UserInterface;
}