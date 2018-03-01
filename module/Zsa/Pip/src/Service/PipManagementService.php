<?php

namespace Zsa\Pip\Service;

use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dao\PipDaoInterface;
use Zsa\Pip\Entity\Enum\PipStatusEnum;

/**
 * Class PipManagementService
 *
 * @package Zsa\Pip\Service
 */
class PipManagementService
{
    /**
     * @var PipDaoInterface
     */
    protected $pipDao;

    /**
     * PipManagementService constructor.
     *
     * @param PipDaoInterface $pipDao
     */
    public function __construct(PipDaoInterface $pipDao)
    {
        $this->pipDao = $pipDao;
    }

    /**
     * @param PipContainerInterface $relative
     *
     * @return bool
     */
    public function removeRelatedPips(PipContainerInterface $relative)
    {
        $result = true;
        $relatedPips = $relative->getAssignedPips();

        foreach ($relatedPips as $pip) {
            $result &= $this->pipDao->tryToRemove($pip, false);
        }

        return $result;
    }

    /**
     * @param PipContainerInterface $relative
     * @param string                $status
     * @param bool                  $save
     *
     * @return bool
     */
    public function changeRelatedPipsStatuses(PipContainerInterface $relative, $status = PipStatusEnum::VALUE_OPENED, $save = true)
    {
        $result = true;
        $relatedPips = $relative->getAssignedPips();

        foreach ($relatedPips as $pip) {
            $pip->setStatus($status);

            if ($save) {
                $result &= $this->pipDao->tryToSave($pip, false);
            }
        }

        return $result;
    }
}
