<?php

namespace Zsa\Pip\Dispatcher;

use Axmit\UserCore\Entity\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Interop\Container\ContainerInterface;
use Zsa\Pip\Collection\PipContainerInterface;
use Zsa\Pip\Dto\PipAttach\PipAttachTo;
use Zsa\Pip\Dto\PipRelation\PipRelationTo;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Service\PipAttachServiceInterface;

/**
 * Class PipAttachDispatcher
 *
 * @package Zsa\Pip\Dispatcher
 */
class PipAttachDispatcher
{
    /**
     * @var ContainerInterface
     */
    protected $serviceManager;

    /**
     * PipAttachingDispatcher constructor.
     *
     * @param ContainerInterface $serviceManager
     */
    public function __construct(ContainerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param PipAttachTo   $pipAttachTo
     * @param UserInterface $user
     *
     * @throws \Exception
     * @return null
     */
    public function dispatch(PipAttachTo $pipAttachTo, UserInterface $user)
    {
        if (!$this->validate($pipAttachTo)) {
            return false;
        }

        /** @var Pip $pip */
        $pip           = $pipAttachTo->getPip();
        $service       = $this->getService($pipAttachTo->getService());
        $relatedEntity = $this->getRelatedEntity($pipAttachTo->getRelated(), $service);

        $relationTo = new PipRelationTo();
        $relationTo->setPip($pip)
                   ->setRelationType($pipAttachTo->getRelationType());

        try {
            switch ($pipAttachTo->getPipAction()) {
                case PipAttachTo::ACTION_PIP_ATTACH:
                    $result = $service->attachPip($relatedEntity, $relationTo, $user);

                    break;
                case PipAttachTo::ACTION_PIP_DETACH:
                    $result = $service->detachPip($relatedEntity, $relationTo, $user);
                    break;
                default:
                    $result = false;
            }
        } catch (\Exception $exception) {
            throw new \Exception('Возникла ошибка при выполнении операции с PIP. Повторите попытку позже');
        }

        return $result;
    }

    /**
     * @param PipAttachTo $pipAttachTo
     *
     * @throws \Exception
     * @return null|Pip[]|ArrayCollection
     */
    public function fetch(PipAttachTo $pipAttachTo)
    {
        $service       = $this->getService($pipAttachTo->getService());
        $relatedEntity = $this->getRelatedEntity($pipAttachTo->getRelated(), $service);

        return $relatedEntity->getAssignedPips();
    }

    /**
     * @param PipAttachTo $pipAttachTo
     *
     * @throws \Exception
     * @return PipContainerInterface
     */
    public function fetchRelated(PipAttachTo $pipAttachTo)
    {
        $service = $this->getService($pipAttachTo->getService());

        return $this->getRelatedEntity($pipAttachTo->getRelated(), $service);
    }

    /**
     * @param PipAttachTo $pipAttachTo
     *
     * @return bool
     * @throws \Exception
     */
    protected function validate(PipAttachTo $pipAttachTo)
    {
        if (!in_array($pipAttachTo->getService(), $this->getAvailableServices())) {
            throw new \Exception(sprintf('Service "%s" not found', $pipAttachTo->getService()));
        }

        if (!in_array($pipAttachTo->getPipAction(), $this->getAvailableActionParams())) {
            throw new \Exception(sprintf('Action "%s" is not available', $pipAttachTo->getPipAction()));
        }

        if (!$pipAttachTo->getPip()) {
            throw new \Exception('PIP not found');
        }

        if (!$pipAttachTo->getRelated()) {
            throw new \Exception('Entity not found');
        }

        return true;
    }

    /**
     * @param string $serviceParam
     *
     * @return PipAttachServiceInterface
     * @throws \Exception
     */
    protected function getService($serviceParam)
    {
        if (!in_array($serviceParam, $this->getAvailableServices())) {
            throw new \Exception(sprintf('Service "%s" not found', $serviceParam));
        }

        $serviceAlias = implode('.', ['hid.service', $serviceParam]);
        $service      = $this->serviceManager->get($serviceAlias);

        if (!$service) {
            throw new \Exception(sprintf('Service "%s" not found', $serviceParam));
        }

        return $service;
    }

    /**
     * @param mixed                     $related
     * @param PipAttachServiceInterface $service
     *
     * @return PipContainerInterface|null
     * @throws \Exception
     */
    protected function getRelatedEntity($related, PipAttachServiceInterface $service)
    {
        if (is_object($related)) {
            $relatedEntity = $related;
        } else {
            $relatedEntity = $service->find($related);
        }

        if (!$relatedEntity) {
            throw new \Exception('Entity not found');
        }

        if (!$relatedEntity instanceof PipContainerInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'relatedEntity must be type of %s, %s given',
                    PipContainerInterface::class,
                    get_class($relatedEntity)
                )
            );
        }

        return $relatedEntity;
    }

    /**
     * @return array
     */
    protected function getAvailableServices()
    {
        return [
            'project',
            'pip',
            'program',
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableActionParams()
    {
        return [
            PipAttachTo::ACTION_PIP_ATTACH,
            PipAttachTo::ACTION_PIP_DETACH,
        ];
    }
}
