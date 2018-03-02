<?php

namespace Zsa\Pip\InputFilter;

use Axmit\UserCore\Entity\User;
use Interop\Container\ContainerInterface;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter as BaseFilter;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zsa\Pip\Entity\Enum\PipStatusEnum;
use Zsa\Pip\Entity\Pip;

/**
 * Class PipEditFilter
 *
 * @package Zsa\Pip\InputFilter
 */
class PipEditFilter extends BaseFilter
{
    const EL_TITLE    = 'title';
    const EL_BODY     = 'body';
    const EL_STATUS   = 'status';
    const EL_ASSIGNEE = 'assignee';
    const EL_PRIORITY = 'priority';
    const EL_PARENT   = 'parent';
    const EL_TYPE     = 'type';

    /**
     * @var ContainerInterface
     */
    protected $inputFilterManager;

    /**
     * PipEditFilter constructor.
     *
     * @param ContainerInterface $inputFilterManager
     */
    public function __construct(ContainerInterface $inputFilterManager)
    {
        $this->inputFilterManager = $inputFilterManager;
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'       => static::EL_TITLE,
                'required'   => true,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'options'                => [
                            'message' => 'Поле обязательно для заполнения',
                            'type'    => NotEmpty::ALL,
                        ],
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'     => static::EL_BODY,
                'required' => false,
                'filters'  => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_ASSIGNEE,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => 'ObjectExists',
                        'options' => [
                            'object_repository' => User::class,
                            'fields'            => ['id'],
                            'message'           => 'User not found',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_PARENT,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => 'ObjectExists',
                        'options' => [
                            'object_repository' => Pip::class,
                            'fields'            => ['id'],
                            'message'           => 'Pip not found',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_STATUS,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                    ],
                    [
                        'name'                   => InArray::class,
                        'options'                => [
                            'haystack' => PipStatusEnum::getSupportedStatuses(),
                        ],
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_TYPE,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'                   => InArray::class,
                        'options'                => [
                            'haystack' => Pip::getAvailableTypes(),
                        ],
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'     => static::EL_PRIORITY,
                'required' => false,
                'filters'  => [
                    ['name' => ToInt::class],
                ],
            ]
        );

    }
}
