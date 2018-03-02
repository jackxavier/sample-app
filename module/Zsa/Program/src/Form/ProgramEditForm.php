<?php

namespace Zsa\Program\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form as BaseForm;
use Zsa\Program\InputFilter\ProgramEditFilter;

/**
 * Class ProgramEditForm
 *
 * @package Zsa\Program\Form
 */
class ProgramEditForm extends BaseForm
{
    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'    => ProgramEditFilter::EL_TITLE,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Название',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProgramEditFilter::EL_DESCRIPTION,
                'type'    => Textarea::class,
                'options' => [
                    'label' => 'Описание',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProgramEditFilter::EL_STATUS,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Статус',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProgramEditFilter::EL_PROJECTS,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'Проекты',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProgramEditFilter::EL_CONTROLLER,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'Контроллер',
                ],
            ]
        );

    }
}