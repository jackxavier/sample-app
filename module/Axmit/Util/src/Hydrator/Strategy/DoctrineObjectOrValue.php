<?php

namespace Axmit\Util\Hydrator\Strategy;

/**
 * Class DoctrineObjectOrValue
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class DoctrineObjectOrValue extends DoctrineObject
{
    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data  (optional) The original data for context.
     *
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (!$value || is_object($value)) {
            return $value;
        }

        $matched = $this->objectRepository->findOneBy([$this->identifier => $value]);
        if (!$matched) {
            return $value;
        }

        return $matched;
    }
}