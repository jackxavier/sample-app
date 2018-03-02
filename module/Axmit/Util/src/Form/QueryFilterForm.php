<?php

namespace Axmit\Util\Form;

use Epos\Dao\Criterion\Filter;
use Zend\Form\ElementInterface;
use Zend\Form\FieldsetInterface;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Class QueryFilterForm
 *
 * @package Axmit\Util\Form
 */
abstract class QueryFilterForm extends EventsProviderForm implements InputFilterProviderInterface
{
    const EL_LIMIT  = Filter::LIMIT_PARAMETER_NAME;
    const EL_OFFSET = Filter::OFFSET_PARAMETER_NAME;

    protected $defaultInputFilterSpecification
        = [
            [
                'name'     => self::EL_LIMIT,
                'required' => false,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ],
            [
                'name'     => self::EL_OFFSET,
                'required' => false,
                'filters'  => [
                    ['name' => 'ToInt'],
                ],
            ],
        ];

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $formSpecification = $this->getThisInputFilterSpecification();

        return array_merge($this->defaultInputFilterSpecification, (array)$formSpecification);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function filterValues($data)
    {
        $filter   = $this->getInputFilter();
        $defaults = $this->getDefaults($this);

        $filter->setData($data);

        $values   = $this->filterEmpty($filter->getValues());
        $defaults = $this->filterEmpty($defaults);

        $values = ArrayUtils::merge($defaults, $values);

        if (!empty($values)) {
            $this->setData($values);
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getQueryWhitelist()
    {
        $defaults = $this->getDefaults($this);

        return array_keys($defaults);
    }

    /**
     * Returns user specified input filter specification
     *
     * @return array
     */
    abstract protected function getThisInputFilterSpecification();

    /**
     * Reutrns `Always to NULL` filter specification (i.e. filter that converts any value to NULL)
     *
     * @return array
     */
    protected function alwaysToNullFilterSpecification()
    {
        return [
            'name'    => 'Callback',
            'options' => [
                'callback' => function () {
                    return null;
                },
            ],
        ];
    }

    /**
     * @param array $values
     *
     * @return array
     */
    protected function filterEmpty(array $values)
    {
        $filtered = [];

        foreach ($values as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            if (is_array($value)) {
                $filtered[$key] = $this->filterEmpty($value);

                if (empty($filtered[$key])) {
                    unset($filtered[$key]);
                }

                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    /**
     * @param ElementInterface $el
     *
     * @return array
     */
    protected function getDefaults(ElementInterface $el)
    {
        $values = [];
        $name   = $el->getName();

        if ($el instanceof FieldsetInterface) {

            $nested       = array_merge($el->getElements(), (array)$el->getFieldsets());
            $nestedValues = [];

            foreach ($nested as $nestedEl) {
                $nestedValues = ArrayUtils::merge($nestedValues, $this->getDefaults($nestedEl));
            }

            if ($el instanceof FormInterface) {
                return $nestedValues;
            }

            $values[$name] = $nestedValues;
        } else {
            $values[$name] = $el->getValue();
        }

        return $values;
    }
}
