<?php

namespace Zsa\Pip\Dto\PipRelation;

use Zend\Paginator\Paginator;

/**
 * Class PipRelationCollection
 *
 * @package Zsa\PIp\Dto\PipRelation
 */
class PipRelationCollection extends Paginator
{
    /**
     * PipRelationCollection constructor.
     *
     * @param \Zend\Paginator\Adapter\AdapterInterface|\Zend\Paginator\AdapterAggregateInterface $adapter
     */
    public function __construct($adapter)
    {
        parent::__construct($adapter);
        $this->setItemCountPerPage(-1);
    }
}