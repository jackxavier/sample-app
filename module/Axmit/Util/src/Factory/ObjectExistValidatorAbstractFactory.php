<?php

namespace Axmit\Util\Factory;

use Axmit\Util\Validator\ArrayObjectExists;
use DoctrineModule\Validator\NoObjectExists;
use DoctrineModule\Validator\ObjectExists;
use DoctrineModule\Validator\UniqueObject;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ObjectExistValidatorAbstractFactory implements AbstractFactoryInterface
{

    protected $supported
        = [
            'ObjectExists'      => ObjectExists::class,
            'NoObjectExists'    => NoObjectExists::class,
            'UniqueObject'      => UniqueObject::class,
            'ArrayObjectExists' => ArrayObjectExists::class,
        ];

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (array_key_exists($requestedName, $this->supported)) {
            return true;
        }
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     *
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('Doctrine\\ORM\\EntityManager');

        if (isset($options['object_repository']) && is_string($options['object_repository'])) {
            $options['object_repository'] = $entityManager->getRepository($options['object_repository']);
        }

        if ($requestedName === 'uniqueobject' && !isset($options['object_manager'])) {
            $options['object_manager'] = $entityManager;
        }

        $validatorClass = $this->supported[$requestedName];

        return new $validatorClass($options);
    }
}
