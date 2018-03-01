<?php

namespace Axmit\Dao\Criterion\Criteria;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\Expr\Value;

/**
 * Class ExpressionBag
 *
 * @package Axmit\Dao\Criterion\Criteria
 * @author  ma4eto <eddiespb@gmail.com>
 */
class ExpressionBag
{

    /**
     * @var string
     */
    protected $field;

    /**
     * @var Expression
     */
    protected $expr;

    /**
     * CriteriaBag constructor.
     *
     * @param string $field
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * @param Expression $x
     */
    public function andX($x = null)
    {
        $this->addExpression(Criteria::expr()->andX($x), CompositeExpression::TYPE_AND);
    }

    /**
     * @param Expression $x
     */
    public function orX($x = null)
    {
        $this->addExpression(Criteria::expr()->orX($x), CompositeExpression::TYPE_OR);
    }

    /**
     * @param $value
     */
    public function value($value)
    {
        $this->addExpression(new Value($value));
    }

    /**
     * @param mixed $value
     */
    public function eq($value)
    {
        $this->addExpression(Criteria::expr()->eq($this->field, $value));
    }

    /**
     * @param mixed $value
     */
    public function gt($value)
    {
        $this->addExpression(Criteria::expr()->gt($this->field, $value));
    }

    /**
     * @param mixed $value
     */
    public function lt($value)
    {
        $this->addExpression(Criteria::expr()->lt($this->field, $value));
    }

    /**
     * @param mixed $value
     */
    public function gte($value)
    {
        $this->addExpression(Criteria::expr()->gte($this->field, $value));
    }

    /**
     * @param mixed $value
     */
    public function lte($value)
    {
        $this->addExpression(Criteria::expr()->lte($this->field, $value));
    }

    /**
     * @param mixed $value
     */
    public function neq($value)
    {
        $this->addExpression(Criteria::expr()->neq($this->field, $value));
    }

    /**
     *
     */
    public function isNull()
    {
        $this->addExpression(Criteria::expr()->isNull($this->field));
    }

    /**
     * @param array $values
     */
    public function in(array $values)
    {
        $this->addExpression(Criteria::expr()->in($this->field, $values));
    }

    /**
     * @param array $values
     */
    public function notIn(array $values)
    {
        $this->addExpression(Criteria::expr()->notIn($this->field, $values));
    }

    /**
     * @param mixed $value
     */
    public function contains($value)
    {
        $this->addExpression(Criteria::expr()->contains($this->field, $value));
    }

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->expr;
    }

    /**
     * @param Expression $expr
     * @param string     $type
     *
     * @return $this
     */
    protected function addExpression(Expression $expr, $type = CompositeExpression::TYPE_AND)
    {
        if (isset($this->expr) && !$expr instanceof Value) {
            $this->expr = new CompositeExpression($type, [$this->expr, $expr]);

            return $this;
        }

        $this->expr = $expr;

        return $this;
    }
}
