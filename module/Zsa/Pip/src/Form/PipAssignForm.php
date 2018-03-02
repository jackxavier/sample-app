<?php

namespace Zsa\Pip\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Form as BaseForm;
use Zsa\Pip\InputFilter\PipAssignFilter;

/**
 * Class PipAssignForm
 *
 * @package Zsa\Pip\Form
 */
class PipAssignForm extends BaseForm
{
    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'    => PipAssignFilter::EL_ASSIGN,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'User',
                ],
            ]
        );
    }
}