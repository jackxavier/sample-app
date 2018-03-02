<?php

namespace Zsa\Pip\InputFilter;

use Axmit\UserCore\Entity\User;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter as BaseFilter;
use Zend\Validator\NotEmpty;

/**
 * Class PipAssignFilter
 *
 * @package Zsa\Pip\InputFilter
 */
class PipAssignFilter extends BaseFilter
{
    const EL_ASSIGN = 'assignee';

    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'       => static::EL_ASSIGN,
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
                    [
                        'name'                   => 'ObjectExists',
                        'options'                => [
                            'object_repository' => User::class,
                            'fields'            => ['id'],
                            'message'           => 'User not found',
                        ],
                        'break_chain_on_failure' => true,
                    ],
                ],
            ]
        );
    }
}