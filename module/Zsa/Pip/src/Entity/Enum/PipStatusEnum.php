<?php

namespace Zsa\Pip\Entity\Enum;

/**
 * Class TaskStatusEnum
 *
 * @package Zsa\Task\Entity\Enum
 */
class PipStatusEnum
{
    const VALUE_BACKLOG  = 'backlog';
    const VALUE_ACTIVE   = 'active';
    const VALUE_SOLVED   = 'solved';
    const VALUE_BLOCKS   = 'blocks';
    const VALUE_CANCELED = 'canceled';
    const VALUE_OPENED   = 'opened';

    /**
     * @var string
     */
    protected $default = self::VALUE_BACKLOG;

    /**
     * @var string
     */
    protected $name = 'PipStatusEnum';

    /**
     * Returns supported scales
     *
     * @return array
     */
    public static function getSupportedStatuses()
    {
        return [
            static::VALUE_BACKLOG,
            static::VALUE_ACTIVE,
            static::VALUE_SOLVED,
            static::VALUE_BLOCKS,
            static::VALUE_CANCELED,
            static::VALUE_OPENED,
        ];
    }

}