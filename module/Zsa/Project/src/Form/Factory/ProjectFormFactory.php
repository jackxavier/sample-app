<?php

namespace Zsa\Project\Form\Factory;

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
use Zsa\Program\Entity\Program;
use Zsa\Project\Dto\Project\ProjectEditTo;
use Zsa\Project\Form\ProjectForm;
use Zsa\Project\InputFilter\ProjectEditFilter;

/**
 * Class ProjectFormFactory
 *
 * @package Zsa\Project\Form\Factory
 */
class ProjectFormFactory extends FormFactory
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
        return $this->attachToForm(new ProjectForm(), $container, new ProjectEditTo());
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
        $inputFilter = $inputFilterManager->get(ProjectEditFilter::class);

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
        $entityManager = $container->get(EntityManager::class);
        $programRepository = $entityManager->getRepository(Program::class);
        $userRepository = $entityManager->getRepository(User::class);

        /** @var HydratorInterface $hydrator */
        $hydrator = new ClassMethods();
        $hydrator->addStrategy(ProjectEditFilter::EL_PROGRAM, new DoctrineObject($programRepository, 'id'));
        $hydrator->addStrategy(ProjectEditFilter::EL_CONTROLLER, new DoctrineObject($userRepository, 'id'));
        $hydrator->addStrategy(
            ProjectEditFilter::EL_ATTENDEES, new ArrayStrategy(new DoctrineObject($userRepository, 'id'))
        );

        return $hydrator;
    }
}