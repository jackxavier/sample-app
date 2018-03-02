<?php
/**
 * Created by PhpStorm.
 * User: jackxavier
 * Date: 07.09.17
 * Time: 16:57
 */

namespace Axmit\Util\Hydrator\Strategy;


use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class BasicCollectionHydratorStrategy
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class BasicCollectionHydratorStrategy extends BasicHydratorStrategy
{
    use ArrayOrTraversableGuardTrait;

    /**
     * @param object $value
     *
     * @return array
     */
    public function extract($value)
    {
        $this->guardForArrayOrTraversable($value);

        $result = [];
        foreach ($value as $item) {
            $result[] = parent::extract($item);
        }

        return $result;
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    public function hydrate($value)
    {
        $this->guardForArrayOrTraversable($value);

        $result = [];
        foreach ($value as $item) {
            $object = parent::hydrate($item);
            $result[] = $object;
        }
        return $result;
    }
}