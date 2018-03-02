<?php

namespace Zsa\Program\InputFilter;

use Axmit\UserCore\Entity\User;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter as BaseFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zsa\Project\Entity\Project;

/**
 * Class ProgramEditFilter
 *
 * @package Zsa\Program\InputFilter
 */
class ProgramEditFilter extends BaseFilter
{
    const EL_TITLE       = 'title';
    const EL_DESCRIPTION = 'description';
    const EL_STATUS      = 'status';
    const EL_PROJECTS    = 'projects';
    const EL_CONTROLLER  = 'controller';

    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'       => self::EL_TITLE,
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
                'name'       => self::EL_DESCRIPTION,
                'required'   => false,
                'filters'    => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 2048,
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'     => self::EL_STATUS,
                'required' => false,
            ]
        );

        $this->add(
            [
                'name'       => self::EL_PROJECTS,
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'ArrayObjectExists',
                        'options' => [
                            'object_repository' => Project::class,
                            'fields'            => ['id'],
                            'message'           => 'Project with id [%value%] was not found',
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