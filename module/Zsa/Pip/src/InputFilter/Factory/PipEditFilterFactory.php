<?php

namespace Zsa\Pip\InputFilter\Factory;

use Zsa\Pip\InputFilter\PipEditFilter;
use Psr\Container\ContainerInterface;
use Zend\InputFilter\InputFilterPluginManager;

/**
 * Class PipEditFilterFactory
 *
 * @package Zsa\Pip\InputFilter\Factory
 */
class PipEditFilterFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipEditFilter
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var InputFilterPluginManager| ContainerInterface $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');

        return new PipEditFilter($inputFilterManager);
    }
}