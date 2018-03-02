<?php

namespace Axmit\Dao\Criterion\Specification;

use Doctrine\Common\Collections\Criteria;

/**
 * Class BuildEvent
 *
 * @package Axmit\Dao\Criterion\Specification
 * @author  ma4eto <eddiespb@gmail.com>
 */
class BuildEvent
{
    /**
     * @var Criteria[]
     */
    protected $appliedCriteria = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param string   $name
     * @param Criteria $criteria
     */
    public function addApplied($name, Criteria $criteria)
    {
        $this->appliedCriteria[$name] = $criteria;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function isApplied($name)
    {
        return array_key_exists($name, $this->appliedCriteria);
    }

    /**
     * @param string $name
     *
     * @return Criteria|null
     */
    public function getApplied($name)
    {
        if (!$this->isApplied($name)) {
            return null;
        }

        return $this->appliedCriteria[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return $default;
    }
}
