<?php

namespace Zsa\Pip\Form\Factory;

use Axmit\UserCore\Entity\User;
use Axmit\Util\Hydrator\Strategy\DoctrineObject;
use Doctrine\ORM\EntityManager;
use Zsa\Pip\Dto\PipAssignee\PipAssigneeTo;
use Zsa\Pip\Form\PipAssignForm;
use Zsa\Pip\InputFilter\PipAssignFilter;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;

/**
 * Class PipAssignFormFactory
 *
 * @package Zsa\Pip\Form\Factory
 */
class PipAssignFormFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PipAssignForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        $userRepository = $entityManager->getRepository(User::class);

        $hydrator = new ClassMethods();
        $hydrator->addStrategy(PipAssignFilter::EL_ASSIGN, new DoctrineObject($userRepository, 'id'));

        $inputFilter = $container->get('InputFilterManager')->get(PipAssignFilter::class);

        $form = new PipAssignForm();
        $form->setObject(new PipAssigneeTo());
        $form->setHydrator($hydrator);
        $form->setInputFilter($inputFilter);

        return $form;
    }
}