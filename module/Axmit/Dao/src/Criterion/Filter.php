<?php
/**
 * File contains Class Filter
 *
 * @since  21.01.2016
 * @author Eduard Posinitskii <eduard.posinitskii@veeam.com>
 */

namespace Axmit\Dao\Criterion;

use Axmit\Dao\Criterion\Criteria\CriteriaBag;
use Axmit\Dao\Criterion\Criteria\ExpressionBag;
use Axmit\Dao\Criterion\Criteria\Serializer;
use Axmit\Dao\Criterion\Hydrator\DefaultFilterHydrator;
use IteratorAggregate;
use Traversable;
use Zend\Hydrator\HydrationInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class Filter
 *
 * @package Axmit\Dao\Criterion
 * @author  Eduard Posinitskii <eduard.posinitskii@veeam.com>
 */
class Filter implements IteratorAggregate
{
    use ArrayOrTraversableGuardTrait;

    const LIMIT_PARAMETER_NAME      = 'limit';
    const OFFSET_PARAMETER_NAME     = 'offset';
    const SORT_PARAMETER_NAME       = 'sort';
    const CONSTRAINT_PARAMETER_NAME = 'constraint';

    /**
     * @var CriteriaBag
     */
    protected $criteriaBag;

    /**
     * @var array
     */
    protected $priority = [];

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param $name
     *
     * @return ExpressionBag
     */
    public function andConstraint($name)
    {
        $this->addPriority($name);

        return $this->getCriteriaBag()->getAnd($name);
    }

    /**
     * @param string $name
     *
     * @return ExpressionBag
     */
    public function orConstraint($name)
    {
        $this->addPriority($name);

        return $this->getCriteriaBag()->getOr($name);
    }

    /**
     * @param string $name
     * @param string $direction
     */
    public function orderBy($name, $direction = 'ASC')
    {
        $this->getCriteriaBag()->getOrder()->orderBy([$name => $direction]);
    }

    /**
     * @param string $name
     * @param string $direction
     */
    public function addOrderBy($name, $direction = 'ASC')
    {
        $order   = $this->getCriteriaBag()->getOrder();
        $orderBy = array_merge($order->getOrderings(), [$name => $direction]);
        $order->orderBy($orderBy);
    }

    /**
     * @param int $value
     */
    public function limit($value)
    {
        $this->getCriteriaBag()->getLimits()->setMaxResults($value);
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->getCriteriaBag()->getLimits()->getMaxResults();
    }

    /**
     * @param int $value
     */
    public function offset($value)
    {
        $this->getCriteriaBag()->getLimits()->setFirstResult($value);
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->getCriteriaBag()->getLimits()->getFirstResult();
    }

    /**
     * @param array              $params
     * @param HydrationInterface $hydrator
     */
    public function fromArray(array $params, HydrationInterface $hydrator = null)
    {
        $hydrator = isset($hydrator) ? $hydrator : new DefaultFilterHydrator();

        $hydrator->hydrate($params, $this);
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        $array = [];
        foreach ($this as $name => $criteria) {
            $array[$name] = $this->getSerializer()->getArrayCopy($criteria);
        }

        return $array;
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->assembleCriteria());
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        if (!$this->serializer) {
            $this->serializer = new Serializer();
        }

        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $name
     */
    protected function addPriority($name)
    {
        if (isset($this->priority[$name])) {
            return;
        }

        $priority              = 100 - count($this->priority);
        $this->priority[$name] = $priority;
    }

    /**
     * @return CriteriaBag
     */
    protected function getCriteriaBag()
    {
        if (!$this->criteriaBag) {
            $this->criteriaBag = new CriteriaBag();
        }

        return $this->criteriaBag;
    }

    /**
     * @return array
     */
    protected function assembleCriteria()
    {
        $criteria = ArrayUtils::merge(
            $this->getCriteriaBag()->getAndCriteria(),
            $this->getCriteriaBag()->getOrCriteria()
        );

        $priority = $this->priority;
        uksort(
            $criteria,
            function ($a, $b) use ($priority) {
                if (!(array_key_exists($a, $priority) && array_key_exists($b, $priority))) {
                    return 0;
                }

                return $priority[$a] < $priority[$b] ? 1 : -1;
            }
        );

        $limitOffsetKey = sprintf('%s.%s', static::LIMIT_PARAMETER_NAME, static::OFFSET_PARAMETER_NAME);

        $criteria[static::SORT_PARAMETER_NAME] = $this->getCriteriaBag()->getOrder();
        $criteria[$limitOffsetKey]             = $this->getCriteriaBag()->getLimits();

        return $criteria;
    }

}
