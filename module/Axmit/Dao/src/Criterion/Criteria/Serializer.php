<?php

namespace Axmit\Dao\Criterion\Criteria;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
use Axmit\Dao\Criterion\Filter;
use RuntimeException;

/**
 * Class Serializer
 *
 * @package Axmit\Dao\Criterion\Criteria
 * @author  ma4eto <eddiespb@gmail.com>
 */
class Serializer extends ExpressionVisitor
{

    /**
     * @param Criteria $criteria
     *
     * @return array
     */
    public function getArrayCopy(Criteria $criteria)
    {
        $structure = [
            Filter::CONSTRAINT_PARAMETER_NAME => $this->dispatch($criteria->getWhereExpression()),
            Filter::OFFSET_PARAMETER_NAME     => $criteria->getFirstResult(),
            Filter::LIMIT_PARAMETER_NAME      => $criteria->getMaxResults(),
            Filter::SORT_PARAMETER_NAME       => $criteria->getOrderings(),
        ];

        return array_filter($structure);
    }

    /**
     * @param array $data
     *
     * @return Criteria
     */
    public function fromArray(array $data)
    {
        $criteria = Criteria::create();

        if (!empty($data[Filter::OFFSET_PARAMETER_NAME])) {
            $criteria->setFirstResult($data[Filter::OFFSET_PARAMETER_NAME]);
        }

        if (!empty($data[Filter::LIMIT_PARAMETER_NAME])) {
            $criteria->setMaxResults($data[Filter::LIMIT_PARAMETER_NAME]);
        }

        if (!empty($data[Filter::SORT_PARAMETER_NAME])) {
            $criteria->orderBy((array)$data[Filter::SORT_PARAMETER_NAME]);
        }

        if (!empty($data[Filter::CONSTRAINT_PARAMETER_NAME])) {
            $this->buildExpressions($criteria, $data[Filter::CONSTRAINT_PARAMETER_NAME]);
        }

        return $criteria;
    }

    /**
     * Serializes a the given criteria to a PHP serialized format.
     *
     * @param Criteria $criteria The criteria to serialize.
     *
     * @return string
     */
    public function serialize(Criteria $criteria)
    {
        return serialize($this->getArrayCopy($criteria));
    }

    /**
     * Unserializes the given string.
     *
     * @param string $data The data to unserialize.
     *
     * @return Criteria
     */
    public function unserialize($data)
    {
        $structure = unserialize($data);

        return $this->fromArray($structure);
    }

    /**
     * Builds the expressions for the given criteria.
     *
     * @param Criteria $criteria  The criteria to build expressions for.
     * @param array    $structure The structure with expressions.
     *
     * @throws RuntimeException
     */
    private function buildExpressions(Criteria $criteria, $structure)
    {
        if (array_key_exists('type', $structure)) {
            foreach ($structure['expressions'] as $expression) {
                $expr = $this->buildExpression($criteria, $expression);
                if (!$expr) {
                    continue;
                }
                switch ($structure['type']) {
                    case CompositeExpression::TYPE_AND:
                        $criteria->andWhere($expr);
                        break;
                    case CompositeExpression::TYPE_OR:
                        $criteria->orWhere($expr);
                        break;
                    default:
                        throw new RuntimeException('Invalid expression type: ' . $structure['type']);
                }
            }
        } else {
            $expr = $this->buildExpression($criteria, $structure);
            $criteria->where($expr);
        }
    }

    /**
     * @param Criteria $criteria
     * @param array    $expression
     *
     * @return Comparison|void
     */
    private function buildExpression(Criteria $criteria, $expression)
    {
        if (array_key_exists('type', $expression)) {
            $this->buildExpressions($criteria, $expression);

            return;
        }
        if (is_object($expression['value'])) {
            $value = $this->buildExpression($criteria, $expression['value']);
        } else {
            $value = new Value($expression['value']);
        }

        return new Comparison($expression['field'], $expression['operator'], $value);
    }

    /**
     * @inheritDoc
     */
    public function walkComparison(Comparison $comparison)
    {
        $value = $this->dispatch($comparison->getValue());

        return [
            'field'    => $comparison->getField(),
            'value'    => $value,
            'operator' => $comparison->getOperator(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function walkCompositeExpression(CompositeExpression $expr)
    {
        $expressionList = [];
        foreach ($expr->getExpressionList() as $child) {
            $expressionList[] = $this->dispatch($child);
        }

        return [
            'type'        => $expr->getType(),
            'expressions' => $expressionList,
        ];
    }

    /**
     * @inheritDoc
     */
    public function walkValue(Value $value)
    {
        if (is_object($value->getValue())) {
            $result = $this->dispatch($value->getValue());
        } else {
            $result = $value->getValue();
        }

        return $result;
    }
}
