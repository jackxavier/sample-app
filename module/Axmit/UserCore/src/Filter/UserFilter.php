<?php

namespace Axmit\UserCore\Filter;

use Axmit\UserCore\Dto\UserTo;
use Axmit\UserCore\Dto\UserViewTo;
use Axmit\UserCore\Dto\UserWebViewTo;
use Axmit\UserCore\Entity\User;
use Axmit\UserCore\Transformer\UserTransformer;
use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;

/**
 * Class UserFilter
 *
 * @package Axmit\UserCore\Filter
 */
class UserFilter implements FilterInterface
{
    const VALUE_MODE_SHORT    = 'short';
    const VALUE_MODE_VIEW     = 'view';
    const VALUE_MODE_WEB_VIEW = 'web_view';

    protected $mode = self::VALUE_MODE_SHORT;

    /**
     * @return array
     */
    public static function getAvailableModes()
    {
        return [
            self::VALUE_MODE_SHORT,
            self::VALUE_MODE_VIEW,
            self::VALUE_MODE_WEB_VIEW,
        ];
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return UserFilter
     */
    public function setMode(string $mode): UserFilter
    {
        if (in_array($mode, self::getAvailableModes())) {
            $this->mode = $mode;
        }

        return $this;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return UserTo|UserViewTo|UserWebViewTo
     */
    public function filter($value)
    {
        if ($value instanceof User) {
            return $value;
        }

        $filtered = null;

        switch ($this->getMode()) {
            case self::VALUE_MODE_VIEW :
                $filtered = UserTransformer::toUserViewTo($value);
                break;
            case self::VALUE_MODE_WEB_VIEW :
                $filtered = UserTransformer::toUserWebViewTo($value);
                break;
            default :
                $filtered = UserTransformer::toUserTo($value);
        }

        return $filtered;
    }
}