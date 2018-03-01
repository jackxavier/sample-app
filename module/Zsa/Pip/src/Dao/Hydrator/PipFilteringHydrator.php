<?php

namespace Zsa\Pip\Dao\Hydrator;


use Axmit\Dao\Criterion\Filter;
use Axmit\Dao\Criterion\Hydrator\DefaultFilterHydrator;
use Zsa\Pip\Dao\Criterion\PipDoctrineSpecification;

/**
 * Class PipFilteringHydrator
 *
 * @package Zsa\Pip\Dao\Hydrator
 */
class PipFilteringHydrator extends DefaultFilterHydrator
{
    /**
     * @param string $name
     * @param mixed  $value
     * @param Filter $filter
     */
    protected function hydrateDefault($name, $value, Filter $filter)
    {
        switch ($name) {
            case 'title':
                $filter->andConstraint($name)->contains($value);
                break;
            case 'order-creation':
                if ($value == 'ASC' || $value == 'DESC') {
                    $filter->orderBy('pip.createdOn', $value);
                }
                break;
            case 'order-priority':
                if ($value == 'ASC' || $value == 'DESC') {
                    $filter->orderBy('pip.priority', $value);
                }
                break;
            case PipDoctrineSpecification::SPEC_PIP_TAG:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_PROGRAM:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_PROJECT:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_PROTOCOL:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_TASK:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_SELF:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_ASSIGNEE:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_IGNORE_ATTACHED:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_IGNORE_UNATTACHED:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_TYPE:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_STATUS:
                $filter->andConstraint($name)->value($value);
                break;
            case PipDoctrineSpecification::SPEC_PIP_IGNORE_STATUS:
                $filter->andConstraint($name)->value($value);
                break;
            default:
                parent::hydrateDefault($name, $value, $filter);
                break;
        }
    }
}
