<?php

namespace Zsa\Program\Form\Factory;

use Axmit\UserCore\Entity\User;
use Axmit\Util\Form\FormFactory;
use Axmit\Util\Hydrator\Strategy\ArrayStrategy;
use Axmit\Util\Hydrator\Strategy\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zsa\Program\Dto\Program\ProgramEditTo;
use Zsa\Program\Form\ProgramEditForm;
use Zsa\Program\InputFilter\ProgramEditFilter;
use Zsa\Project\Entity\Project;

/**
 * Class ProgramEditFormFactory
 *
 * @package Zsa\Program\Form\Factory
 */
class ProgramEditFormFactory extends FormFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Form
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->attachToForm(new ProgramEditForm(), $container, new ProgramEditTo());
    }

    /**
     * @param ContainerInterface|null $container
     *
     * @return mixed
     */
    public function getInputFilter(ContainerInterface $container = null, $options = null)
    {
        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');
        /** ProgramEditFilter $inputFilter */
        $inputFilter = $inputFilterManager->get(ProgramEditFilter::class);

        return $inputFilter;
    }

    /**
     * @param ContainerInterface|null $container
     *
     * @return ClassMethods|HydratorInterface
     */
    public function getHydrator(ContainerInterface $container = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager     = $container->get(EntityManager::class);
        $projectRepository = $entityManager->getRepository(Project::class);
        $userRepository    = $entityManager->getRepository(User::class);
        /** @var HydratorInterface $hydrator */
        $hydrator = new ClassMethods();

        $hydrator->addStrategy(
            ProgramEditFilter::EL_PROJECTS, new ArrayStrategy(
                                              new DoctrineObject($projectRepository, 'id')
                                          )
        );

        $hydrator->addStrategy(ProgramEditFilter::EL_CONTROLLER, new DoctrineObject($userRepository, 'id'));

        return $hydrator;
    }
}