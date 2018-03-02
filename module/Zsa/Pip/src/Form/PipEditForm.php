<?php

namespace Zsa\Pip\Form;

use Zsa\Pip\InputFilter\PipEditFilter;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form as BaseForm;

/**
 * Class PipEditForm
 *
 * @package Zsa\Pip\Form
 */
class PipEditForm extends BaseForm
{
    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'    => PipEditFilter::EL_TITLE,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Название',
                ],
            ]
        );

        $this->add(
            [
                'name'    => PipEditFilter::EL_TYPE,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Тип',
                ],
            ]
        );

        $this->add(
            [
                'name'    => PipEditFilter::EL_BODY,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Тело',
                ],
            ]
        );

        $this->add(
            [
                'name'    => PipEditFilter::EL_STATUS,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Текущий статус',
                ],
            ]
        );

        $this->add(
            [
                'name' => PipEditFilter::EL_ASSIGNEE,
                'type' => Hidden::class,
            ]
        );

        $this->add(
            [
                'name' => PipEditFilter::EL_PARENT,
                'type' => Hidden::class,
            ]
        );

        $this->add(
            [
                'name'    => PipEditFilter::EL_PRIORITY,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Приоритет',
                ],
            ]
        );
    }
}