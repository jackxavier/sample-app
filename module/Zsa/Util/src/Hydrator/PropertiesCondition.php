<?php

namespace Zsa\Util\Hydrator;

/**
 * Class PropertiesCondition
 *
 * @package Zsa\Util\Hydrator
 */
class PropertiesCondition
{
    const PROPERTY_EQ         = 'eq';
    const PROPERTY_LESS_EQ    = 'less_eq';
    const PROPERTY_LESS       = 'less';
    const PROPERTY_GREATER_EQ = 'greater_eq';
    const PROPERTY_GREATER    = 'greater';
    const PROPERTY_NOT        = 'not';

    /**
     * @param      $propertyValue
     * @param null $type
     * @param null $value
     *
     * @return bool
     */
    public static function resolveCondition($propertyValue, $type = null, $value = null)
    {
        $type = isset($type) ? $type : static::PROPERTY_NOT;

        switch ($type) {
            case static::PROPERTY_EQ :
                return $propertyValue === $value;
                break;
            case static::PROPERTY_LESS_EQ :
                return $propertyValue <= $value;
                break;
            case static::PROPERTY_LESS :
                return $propertyValue < $value;
                break;
            case static::PROPERTY_GREATER_EQ :
                return $propertyValue >= $value;
                break;
            case static::PROPERTY_GREATER :
                return $propertyValue > $value;
                break;
            case static::PROPERTY_NOT :
                return $propertyValue !== $value;
                break;
        }

        return false;
    }

}
