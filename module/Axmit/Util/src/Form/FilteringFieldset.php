<?php

namespace Axmit\Util\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class FilteringFieldset
 *
 * @package Axmit\Util\Form
 */
class FilteringFieldset extends Fieldset implements InputFilterProviderInterface
{
    const EL_FIELD = 'field';
    const EL_VALUE = 'value';

    public function init()
    {
        $this->add(
            [
                'type'    => 'Radio',
                'name'    => static::EL_FIELD,
                'options' => [
                    'label' => 'Поле',
                ],
            ]
        );

        $this->add(
            [
                'type'    => 'Radio',
                'name'    => static::EL_VALUE,
                'options' => [
                    'label' => 'Значение',
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
                'name'     => static::EL_VALUE,
                'required' => false,
            ],
        ];
    }

    /**
     * @param array $options
     */
    public function setFilteringOptions(array $options)
    {
        $el = $this->get(static::EL_FIELD);
        $el->setValueOptions($options);
    }
}
