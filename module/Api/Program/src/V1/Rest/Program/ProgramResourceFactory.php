<?php

namespace Api\Program\V1\Rest\Program;

use Axmit\Monolog\Logger\AppLogger\AppLogger;
use Axmit\Util\Logger\AbstractLoggerFactory;
use Epos\UserCore\Service\UserService;
use Zsa\Program\Filter\ProgramFilter;
use Zsa\Program\Service\ProgramService;
use Interop\Container\ContainerInterface;

/**
 * Class ProgramResourceFactory
 *
 * @package Api\Program\V1\Rest\Program
 */
class ProgramResourceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProgramResource
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ProgramService $programService */
        $programService = $container->get(ProgramService::class);
        /** @var UserService $userService */
        $userService = $container->get(UserService::class);
        /** @var ProgramFilter $positionFilter */
        $employeeFilter = $container->get(ProgramFilter::class);
        /** @var ContainerInterface $formContainer */
        $formContainer = $container->get('FormElementManager');
        /** @var AppLogger $appLogger */
        $appLogger = $container->get(AbstractLoggerFactory::assembleNameForChannel());

        return new ProgramResource($programService, $userService, $employeeFilter, $formContainer, $appLogger);
    }
}