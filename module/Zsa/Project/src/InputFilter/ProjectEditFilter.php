<?php

namespace Zsa\Project\InputFilter;

use Axmit\UserCore\Entity\User;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter as BaseFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zsa\Program\Entity\Program;

/**
 * Class ProjectEditFilter
 *
 * @package Zsa\Project\InputFilter
 */
class ProjectEditFilter extends BaseFilter
{
    const  EL_TITLE       = 'title';
    const  EL_CODE        = 'code';
    const  EL_DESCRIPTION = 'description';
    const  EL_STATUS      = 'status';
    const  EL_ATTENDEES   = 'attendees';
    const  EL_PROGRAM     = 'program';
    const  EL_CONTROLLER  = 'controller';

    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'     => static::EL_STATUS,
                'required' => false,
            ]
        );

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
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_CODE,
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
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_ATTENDEES,
                'required'   => false,
                'validators' => [
                    [
                        'name'                   => 'ArrayObjectExists',
                        'options'                => [
                            'object_repository' => User::class,
                            'fields'            => ['id'],
                            'message'           => 'User with id = [%value%] was not found',
                        ],
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_DESCRIPTION,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'     => 3,
                            'message' => 'Описание проекта должно быть больше %min% символов',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_PROGRAM,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => 'ObjectExists',
                        'options' => [
                            'object_repository' => Program::class,
                            'fields'            => ['id'],
                            'message'           => 'Программа не найдена',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => static::EL_CONTROLLER,
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
                            'message'           => 'User with id = [%value%] was not found',
                        ],
                    ],
                ],
            ]
        );
    }
}