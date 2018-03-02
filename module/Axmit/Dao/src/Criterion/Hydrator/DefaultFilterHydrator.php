<?php

namespace Axmit\Dao\Criterion\Hydrator;

use Axmit\Util\Form\SortableFieldset;
use Axmit\Dao\Criterion\Filter;
use InvalidArgumentException;
use Zend\Hydrator\HydrationInterface;

/**
 * Class DefaultFilterHydrator
 *
 * @package Axmit\Dao\Criterion\Hydrator
 * @author  ma4eto <eddiespb@gmail.com>
 */
class DefaultFilterHydrator implements HydrationInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Filter) {
            throw new InvalidArgumentException(
                sprintf('object MUST be an instance of %s', Filter::class)
            );
        }

        foreach ($data as $name => $value) {
            switch ($name) {
                case Filter::LIMIT_PARAMETER_NAME:
                    $this->hydrateLimit($value, $object);
                    break;
                case Filter::OFFSET_PARAMETER_NAME:
                    $this->hydrateOffset($value, $object);
                    break;
                case Filter::SORT_PARAMETER_NAME:
                    $this->hydrateSort($value, $object);
                    break;
                default:
                    $this->hydrateDefault($name, $value, $object);
                    break;
            }
        }
    }

    /**
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateLimit($value, Filter $filter)
    {
        $filter->limit($value);
    }

    /**
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateOffset($value, Filter $filter)
    {
        $filter->offset($value);
    }

    /**
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateSort($value, Filter $filter)
    {
        if (!is_array($value)) {
            $filter->orderBy($value, 'ASC');

            return;
        }

        $field     = isset($value[SortableFieldset::EL_FIELD]) ? $value[SortableFieldset::EL_FIELD] : null;
        $direction = isset($value[SortableFieldset::EL_DIRECTION]) ? $value[SortableFieldset::EL_DIRECTION] : 'ASC';

        if (empty($field)) {
            return;
        }

        $filter->orderBy($field, $direction);
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateDefault($name, $value, Filter $filter)
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }

        if (is_array($value)) {
            $filter->andConstraint($name)->in($value);

            return;
        }

        $filter->andConstraint($name)->eq($value);
    }
}
