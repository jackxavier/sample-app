<?php

namespace Zsa\Pip\Form\Factory;

use Axmit\UserCore\Entity\User;
use Axmit\Util\Hydrator\Strategy\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\PluginManagerInterface;
use Zsa\Pip\Dto\Pip\PipEditTo;
use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Form\PipEditForm;
use Zsa\Pip\InputFilter\PipEditFilter;

/**
 * Class PipEditFormFactory
 *
 * @package Zsa\Pip\Form\Factory
 */
class PipEditFormFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipEditForm
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new ClassMethods();
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        $pipAssigneeRepository = $entityManager->getRepository(User::class);
        $pipParentRepository   = $entityManager->getRepository(Pip::class);

        $hydrator->addStrategy(PipEditFilter::EL_ASSIGNEE, new DoctrineObject($pipAssigneeRepository, 'id'));
        $hydrator->addStrategy(PipEditFilter::EL_PARENT, new DoctrineObject($pipParentRepository, 'id'));


        /** @var ContainerInterface|PluginManagerInterface $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');
        /** @var PipEditFilter $inputFilter */
        $inputFilter = $inputFilterManager->get(PipEditFilter::class);

        $form = new PipEditForm();
        $form->setHydrator($hydrator);
        $form->setObject(new PipEditTo());
        $form->setInputFilter($inputFilter);

        return $form;
    }
}