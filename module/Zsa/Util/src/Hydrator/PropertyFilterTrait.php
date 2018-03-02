<?php

namespace Zsa\Util\Hydrator;

use Zend\Hydrator\Filter\FilterComposite;
use Zend\Hydrator\Filter\MethodMatchFilter;

/**
 * Trait PropertyFilterTrait
 *
 * @package Zsa\Util\Hydrator
 */
trait PropertyFilterTrait
{
    /**
     * @param array $properties
     * @param bool  $exclude
     *
     * @return FilterComposite
     */
    public function getPropertyFilter($properties, $exclude = true)
    {
        $filter = new FilterComposite();
        foreach ($properties as $property) {
            $filter->addFilter(
                $property,
                new MethodMatchFilter($property, $exclude),
                $exclude === true ? FilterComposite::CONDITION_AND : FilterComposite::CONDITION_OR
            );
        }

        return $filter;
    }
}
