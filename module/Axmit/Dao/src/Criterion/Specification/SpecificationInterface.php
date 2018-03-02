<?php

namespace Axmit\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface SpecificationInterface
 *
 * @package Axmit\Dao\Criterion\Specification
 * @author  ma4eto <eddiespb@gmail.com>
 */
interface SpecificationInterface
{
    /**
     * @param Criteria   $criteria
     * @param BuildEvent $event
     *
     * @return bool
     */
    public function apply(Criteria $criteria, BuildEvent $event);
}
