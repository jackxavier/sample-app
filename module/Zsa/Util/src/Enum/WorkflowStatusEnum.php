<?php

namespace Zsa\Util\Enum;

/**
 * Class WorkflowStatus
 *
 * @package Zsa\Util\Enum
 */
class WorkflowStatusEnum
{
    const VALUE_STATUS_OPENED         = 1;
    const VALUE_STATUS_ON_HOLD        = 2;
    const VALUE_STATUS_IN_PREPARATION = 3;
    const VALUE_STATUS_REOPENED       = 4;
    const VALUE_STATUS_CLOSED         = 5;
    const VALUE_STATUS_ARCHIVED       = 10;

    const VALUE_STATUS_OPENED_STR         = 'opened';
    const VALUE_STATUS_ON_HOLD_STR        = 'on_hold';
    const VALUE_STATUS_IN_PREPARATION_STR = 'in_preparation';
    const VALUE_STATUS_REOPENED_STR       = 'reopened';
    const VALUE_STATUS_CLOSED_STR         = 'closed';
    const VALUE_STATUS_ARCHIVED_STR       = 'archived';

    /**
     * @return array
     */
    public static function getAvailableStatuses()
    {
        return [
            static::VALUE_STATUS_OPENED         => static::VALUE_STATUS_OPENED_STR,
            static::VALUE_STATUS_ON_HOLD        => static::VALUE_STATUS_ON_HOLD_STR,
            static::VALUE_STATUS_IN_PREPARATION => static::VALUE_STATUS_IN_PREPARATION_STR,
            static::VALUE_STATUS_REOPENED       => static::VALUE_STATUS_REOPENED_STR,
            static::VALUE_STATUS_CLOSED         => static::VALUE_STATUS_CLOSED_STR,
            static::VALUE_STATUS_ARCHIVED       => static::VALUE_STATUS_ARCHIVED_STR,
        ];
    }

    /**
     * @return array
     */
    public static function getConvertedAvailableStatuses()
    {
        return [
            self::VALUE_STATUS_OPENED_STR         => self::VALUE_STATUS_OPENED,
            self::VALUE_STATUS_ON_HOLD_STR        => self::VALUE_STATUS_ON_HOLD,
            self::VALUE_STATUS_IN_PREPARATION_STR => self::VALUE_STATUS_IN_PREPARATION,
            self::VALUE_STATUS_REOPENED_STR       => self::VALUE_STATUS_REOPENED,
            self::VALUE_STATUS_CLOSED_STR         => self::VALUE_STATUS_CLOSED,
            self::VALUE_STATUS_ARCHIVED_STR       => self::VALUE_STATUS_ARCHIVED,
        ];
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function convertStatusToInt($value)
    {
        if (!in_array($value, self::getAvailableStatuses())) {
            throw new \InvalidArgumentException(sprintf('The type "%s" is not supported.', $value));
        }

        return self::getConvertedAvailableStatuses()[$value];
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function convertStatusToString($value)
    {
        if (!in_array($value, self::getConvertedAvailableStatuses())) {
            throw new \InvalidArgumentException(sprintf('The type "%s" is not supported.', $value));
        }

        return self::getAvailableStatuses()[$value];
    }
}
