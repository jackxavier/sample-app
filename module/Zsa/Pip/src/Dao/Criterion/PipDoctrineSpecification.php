<?php

namespace Zsa\Pip\Dao\Criterion;

use Axmit\Dao\Criterion\Specification\Doctrine\DoctrineSpecificationManager;

/**
 * Class PipTagDoctrineSpecification
 *
 * @package Zsa\Pip\Dao\Criterion
 */
class PipDoctrineSpecification extends DoctrineSpecificationManager
{
    const SPEC_PIP_TAG               = 'tags';
    const SPEC_PIP_PROGRAM           = 'program';
    const SPEC_PIP_PROJECT           = 'project';
    const SPEC_PIP_PROTOCOL          = 'protocol';
    const SPEC_PIP_TASK              = 'task';
    const SPEC_PIP_SELF              = 'pip';
    const SPEC_PIP_ASSIGNEE          = 'assignee';
    const SPEC_PIP_IGNORE_ATTACHED   = 'ignoreAttached';
    const SPEC_PIP_IGNORE_UNATTACHED = 'ignoreUnattached';
    const SPEC_PIP_TYPE              = 'type';
    const SPEC_PIP_STATUS            = 'status';
    const SPEC_PIP_IGNORE_STATUS     = 'ignoreStatus';

    const ALIAS_PIP_RELATION          = 'prel';
    const ALIAS_PIP_PROGRAM_RELATION  = 'prelprog';
    const ALIAS_PIP_PROJECT_RELATION  = 'prelproj';
    const ALIAS_PIP_PROTOCOL_RELATION = 'prelprot';
    const ALIAS_PIP_SELF_RELATION     = 'prelself';
    const ALIAS_PIP_TASK_RELATION     = 'preltask';
    const ALIAS_PIP_TAG               = 'ptag';
}
