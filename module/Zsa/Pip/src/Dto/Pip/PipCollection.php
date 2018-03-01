<?php

namespace Zsa\Pip\Dto\Pip;

use Zend\Paginator\Paginator;

/**
 * Class PipCollection
 *
 * @package Zsa\Pip\Dto\Pip
 */
class PipCollection extends Paginator
{
    /**
     * PipCollection constructor.
     *
     * @param \Zend\Paginator\Adapter\AdapterInterface|\Zend\Paginator\AdapterAggregateInterface $adapter
     */
    public function __construct($adapter)
    {
        parent::__construct($adapter);
        $this->setItemCountPerPage(-1);
    }
}