<?php

namespace Zsa\Project\Form;

use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form as BaseForm;
use Zsa\Project\InputFilter\ProjectEditFilter;

/**
 * Class ProjectForm
 *
 * @package Zsa\Project\Form
 */
class ProjectForm extends BaseForm
{
    /**
     * @return void
     */
    public function init()
    {
        $this->add(
            [
                'name'    => ProjectEditFilter::EL_STATUS,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Статус',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_TITLE,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Название',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_CODE,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Код',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_DESCRIPTION,
                'type'    => Text::class,
                'options' => [
                    'label' => 'Описание',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_ATTENDEES,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'Участники',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_PROGRAM,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'Программа',
                ],
            ]
        );

        $this->add(
            [
                'name'    => ProjectEditFilter::EL_CONTROLLER,
                'type'    => Hidden::class,
                'options' => [
                    'label' => 'Контроллер',
                ],
            ]
        );
    }
}