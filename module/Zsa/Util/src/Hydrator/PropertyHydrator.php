<?php

namespace Zsa\Util\Hydrator;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\Filter\FilterComposite;

/**
 * Class PropertyHydrator
 *
 * @package Zsa\Util\Hydrator
 */
class PropertyHydrator extends ClassMethods
{
    use PropertyFilterTrait;

    /**
     * @param object $object
     *
     * @return array
     */
    public function extract($object)
    {
        $objectProperties = $this->extractNotNullableFields($object);
        $this->addFilter(
            'property', $this->getPropertyFilter($objectProperties, false), FilterComposite::CONDITION_AND
        );

        return parent::extract($object);
    }

    /**
     * @param      $object
     * @param      $conditional
     * @param null $value
     *
     * @return array|null
     */
    protected function extractNotNullableFields(
        $object,
        $conditional = PropertiesCondition::PROPERTY_NOT,
        $value = null
    ) {

        $extractedFields = [];
        if (!is_object($object)) {
            return null;
        }

        $objectClass = get_class($object);
        if (!$objectClass) {
            return null;
        }

        $methods = get_class_methods($objectClass);
        if (empty($methods) && !is_array($methods)) {
            return null;
        }

        /** @var callable | string $method */
        foreach ($methods as $method) {
            if (!preg_match('|^get([A-Z][\w]+)|', $method) && !preg_match('|^is([A-Z][\w]+)|', $method)) {
                continue;
            }

            $methodResult = $object->$method();

            if (PropertiesCondition::resolveCondition($methodResult, $conditional, $value)) {
                $extractedFields[] = $method;
            }
        }

        return $extractedFields;
    }
}
