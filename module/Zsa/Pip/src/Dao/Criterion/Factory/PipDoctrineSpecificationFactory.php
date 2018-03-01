<?php

namespace Zsa\Pip\Dao\Criterion\Factory;

use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipAssigneeSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipIgnoreAttachedSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipIgnoreStatusSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipIgnoreUnattachedSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipProgramRelationSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipProjectRelationSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipProtocolRelationSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipSelfRelationSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipStatusSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipTagSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipTaskRelationSpecification;
use Zsa\Pip\Dao\Criterion\Specification\PipTypeSpecification;
use Interop\Container\ContainerInterface;

/**
 * Class PipDoctrineSpecificationFactory
 *
 * @package Zsa\Pip\Dao\Criterion
 */
class PipDoctrineSpecificationFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipDoctrineSpecification
     */
    public function __invoke(ContainerInterface $container)
    {
        $specificationManager = new PipDoctrineSpecification();
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_PROGRAM, new PipProgramRelationSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_PROJECT, new PipProjectRelationSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_SELF, new PipSelfRelationSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_ASSIGNEE, new PipAssigneeSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_IGNORE_ATTACHED, new PipIgnoreAttachedSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_IGNORE_UNATTACHED, new PipIgnoreUnattachedSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_TYPE, new PipTypeSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_STATUS, new PipStatusSpecification());
        $specificationManager->addSpecification(PipDoctrineSpecification::SPEC_PIP_IGNORE_STATUS, new PipIgnoreStatusSpecification());

        return $specificationManager;
    }
}