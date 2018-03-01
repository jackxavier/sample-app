<?php

namespace Axmit\UserCore\Transformer;

use Axmit\UserCore\Dto\UserTo;
use Axmit\UserCore\Dto\UserViewTo;
use Axmit\UserCore\Dto\UserWebViewTo;
use Axmit\UserCore\Entity\User;
use DateTime;

/**
 * Class UserTransformer
 *
 * @package Axmit\UserCore\Transformer
 */
class UserTransformer
{
    /**
     * @param User        $user
     * @param UserTo|null $userTo
     *
     * @return UserTo
     */
    public static function toUserTo(User $user, UserTo $userTo = null)
    {
        if (!$userTo) {
            $userTo = new UserTo();
        }

        $userTo->setId($user->getId())
               ->setName($user->getName());

        return $userTo;
    }

    /**
     * @param User            $user
     * @param UserViewTo|null $userViewTo
     *
     * @return UserTo
     */
    public static function toUserViewTo(User $user, UserViewTo $userViewTo = null)
    {
        if (!$userViewTo) {
            $userViewTo = new UserViewTo();
        }

        $userViewTo->setDefaultEmail($user->getDefaultEmail()->getEmail());

        return self::toUserTo($user, $userViewTo);
    }

    /**
     * @param User               $user
     * @param UserWebViewTo|null $userWebViewTo
     *
     * @return UserTo
     */
    public static function toUserWebViewTo(User $user, UserWebViewTo $userWebViewTo = null)
    {
        if (!$userWebViewTo) {
            $userWebViewTo = new UserWebViewTo();
        }

        $userWebViewTo->setCreatedOn($user->getCreatedOn()->format(DateTime::ATOM));

        if ($user->getUpdatedOn()) {
            $userWebViewTo->setUpdatedOn($user->getUpdatedOn()->format(DateTime::ATOM));
        }

        return self::toUserViewTo($user, $userWebViewTo);
    }
}