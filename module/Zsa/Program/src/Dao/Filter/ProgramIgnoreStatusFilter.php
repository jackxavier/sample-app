<?php

namespace Zsa\Program\Dao\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Zsa\Program\Entity\Program;
use Zsa\Util\Enum\WorkflowStatusEnum;

/**
 * Class ProgramIgnoreStatusFilter
 *
 * @package Zsa\Program\Dao\Filter
 */
class ProgramIgnoreStatusFilter extends SQLFilter
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
        if ($targetEntity->getReflectionClass()->getName() !== Program::class) {
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
