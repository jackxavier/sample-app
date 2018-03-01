<?php

namespace Axmit\Dao\Criterion\Specification;

use Axmit\Dao\Criterion\Filter;

/**
 * Interface SpecificationManager
 *
 * @package Axmit\Dao\Criterion\Adapter
 * @author  ma4eto <eddiespb@gmail.com>
 */
interface SpecificationManager
{
    /**
     * @param string                 $name
     * @param SpecificationInterface $spec
     *
     * @return void
     */
    public function addSpecification($name, SpecificationInterface $spec);

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasSpecification($name);

    /**
     * @param string $name
     *
     * @return SpecificationInterface
     */
    public function getSpecification($name);

    /**
     * @param Filter $filter
     *
     * @return void
     */
    public function apply(Filter $filter);
}
