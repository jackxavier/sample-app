<?php

namespace Zsa\Pip\Entity\Enum;

/**
 * Class PipRelationsEnum
 *
 * @package Zsa\Pip\Entity\Enum
 */
class PipRelationsEnum
{
    const VALUE_BLOCK  = 'block';
    const VALUE_SOLVES = 'solve';
    const VALUE_CLONE  = 'clone';
    const VALUE_CAUSE  = 'cause';
    const VALUE_RELATE = 'relate';

    /**
     * @var string
     */
    protected $default = self::VALUE_RELATE;

    /**
     * @var string
     */
    protected $name = 'PipRelationsEnum';

    /**
     * Returns supported scales
     *
     * @return array
     */
    public static function getSupportedRelations()
    {
        return [
            static::VALUE_BLOCK,
            static::VALUE_CAUSE,
            static::VALUE_CLONE,
            static::VALUE_SOLVES,
            static::VALUE_RELATE,
        ];
    }

}