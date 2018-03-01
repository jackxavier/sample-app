<?php

namespace Axmit\Util\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class SortableFieldset
 *
 * @package Axmit\Util\Form
 */
class SortableFieldset extends Fieldset implements InputFilterProviderInterface
{
    const EL_FIELD     = 'field';
    const EL_DIRECTION = 'direction';

    public function init()
    {
        $this->add(
            [
                'type'    => 'Radio',
                'name'    => static::EL_FIELD,
                'options' => [
                    'label' => 'Сортировка',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Radio',
                'name'       => static::EL_DIRECTION,
                'options'    => [
                    'label'         => 'Порядок',
                    'value_options' => [
                        'ASC'  => 'Возр',
                        'DESC' => 'Убыв',
                    ],
                ],
                'attributes' => [
                    'value' => 'ASC',
                ],
            ]
        );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            [
                'name'     => static::EL_FIELD,
                'required' => false,
            ],
            [
                'name'     => static::EL_DIRECTION,
                'required' => false,
            ],
        ];
    }

    /**
     * @param array $options
     * @param mixed $default
     */
    public function setSortOptions(array $options, $default = null)
    {
        $el = $this->get(static::EL_FIELD);
        $el->setValueOptions($options);

        $default = $default ?: array_keys($options)[0];
        $el->setValue($default)->setAttribute('value', $default);
    }
}
