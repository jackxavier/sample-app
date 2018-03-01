<?php

namespace Axmit\UserCore\Dto;

use DateTime;
/**
 * Class UserWebViewTo
 *
 * @package Axmit\UserCore\Dto
 */
class UserWebViewTo extends UserViewTo
{
    /**
     * @var null|DateTime|string
     */
    protected $createdOn = null;

    /**
     * @var null|DateTime|string
     */
    protected $updatedOn = null;

    /**
     * @return null|DateTime|string
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param null|DateTime|string $createdOn
     *
     * @return UserWebViewTo
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return null|DateTime|string
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param null|DateTime|string $updatedOn
     *
     * @return UserWebViewTo
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }
    
    
}