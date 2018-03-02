<?php

namespace Zsa\Pip\Collection;

use Zsa\Pip\Entity\Pip;

/**
 * Trait UpdatePipCollectionTrait
 *
 * @package Zsa\Pip\Collection
 */
trait UpdatePipCollectionTrait
{
    /**
     * @param PipContainerInterface $container
     * @param                       $pips
     *
     * @return void
     */
    protected function updatePips(PipContainerInterface $container, $pips)
    {
        $pipComparativeCollection = new PipComparativeCollection($container);

        foreach ($pips as $pip) {
            if (!$pip instanceof Pip) {
                continue;
            }
            $pipComparativeCollection->addPip($pip);
        }

        $new     = $pipComparativeCollection->getNew();
        $removed = $pipComparativeCollection->findRemoved();

        unset($pipComparativeCollection);

        foreach ($removed as $removedPip) {
            $container->removePip($removedPip);
        }

        foreach ($new as $newPip) {
            $container->assignPip($newPip);
        }
    }
}