<?php

namespace Zsa\Project\Dao\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Zsa\Project\Entity\Project;
use Zsa\Util\Enum\WorkflowStatusEnum;

/**
 * Class ProjectIgnoreStatusFilter
 *
 * @package Zsa\Project\Dao\Filter
 */
class ProjectIgnoreStatusFilter extends SQLFilter
{
    const FILTER_PARAM_STATUS = 'status';

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string        $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->getReflectionClass()->getName() !== Project::class) {
            return '';
        }

        if (!$targetEntity->hasField('status')) {
            return '';
        }

        if (!$this->hasParameter(self::FILTER_PARAM_STATUS)) {
            $this->setParameter(self::FILTER_PARAM_STATUS, WorkflowStatusEnum::VALUE_STATUS_ARCHIVED);
        }

        $str = sprintf(
            '%s.status <> %s',
            $targetTableAlias,
            $this->getParameter(self::FILTER_PARAM_STATUS)
        );

        return $str;
    }
}
