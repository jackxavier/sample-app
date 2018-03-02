<?php

namespace Zsa\Util\Doctrine\Filter;

use Doctrine\ORM\EntityManager;

/**
 * Trait DisableFilterTrait
 *
 * @package Zsa\Util\Doctrine\Filter
 */
trait DisableFilterTrait
{
    /**
     * @param EntityManager $entityManager
     * @param               $filterName
     *
     * @return void
     */
    protected function disableFilter(EntityManager $entityManager, $filterName)
    {
        if ($entityManager->getFilters()->isEnabled($filterName)) {
            $entityManager->getFilters()->disable($filterName);
        }
    }
}
