<?php

namespace Axmit\Dao\Criterion\Criteria;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Value;

/**
 * Class CriteriaBag
 *
 * @package Axmit\Dao\Criterion\Criteria
 * @author  ma4eto <eddiespb@gmail.com>
 */
class CriteriaBag
{
    /**
     * @var ExpressionBag[]
     */
    protected $and = [];

    /**
     * @var ExpressionBag[]
     */
    protected $or = [];

    /**
     * @var Criteria
     */
    protected $order;

    /**
     * @var Criteria
     */
    protected $limits;

    /**
     * @param string $name
     *
     * @return ExpressionBag
     */
    public function getAnd($name)
    {
        if (array_key_exists($name, $this->and)) {
            return $this->and[$name];
        }

        $this->and[$name] = new ExpressionBag($name);

        return $this->and[$name];
    }

    /**
     * @param string $name
     *
     * @return ExpressionBag
     */
    public function getOr($name)
    {
        if (array_key_exists($name, $this->or)) {
            return $this->or[$name];
        }

        $this->or[$name] = new ExpressionBag($name);

        return $this->or[$name];
    }

    /**
     * @return Criteria
     */
    public function getOrder()
    {
        if (!$this->order) {
            $this->order = Criteria::create();
        }

        return $this->order;
    }

    /**
     * @return Criteria
     */
    public function getLimits()
    {
        if (!$this->limits) {
            $this->limits = Criteria::create();
        }

        return $this->limits;
    }

    /**
     * @return Criteria[]
     */
    public function getAndCriteria()
    {
        $criteria = [];

        foreach ($this->and as $name => $bag) {
            if (!$expr = $bag->getExpression()) {
                continue;
            }

            $criteria[$name] = Criteria::create()->where($expr);
        }

        return $criteria;
    }

    /**
     * @return Criteria[]
     */
    public function getOrCriteria()
    {
        $criteria = [];

        foreach ($this->or as $name => $bag) {
            if (!$expr = $bag->getExpression()) {
                continue;
            }

            if ($expr instanceof Value) {
                $criteria[$name] = Criteria::create()->where($expr);
                continue;
            }

            $criteria[$name] = Criteria::create()->orWhere($expr);
        }

        return $criteria;
    }
}
