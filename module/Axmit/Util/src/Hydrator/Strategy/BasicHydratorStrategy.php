<?php
/**
 * Created by PhpStorm.
 * User: jackxavier
 * Date: 07.09.17
 * Time: 16:56
 */

namespace Axmit\Util\Hydrator\Strategy;

use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class BasicHydratorStrategy
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class BasicHydratorStrategy implements StrategyInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected $className;

    /**
     * @param HydratorInterface $hydrator
     * @param string            $className
     */
    public function __construct(HydratorInterface $hydrator, $className)
    {
        $this->className = $className;
        $this->hydrator  = $hydrator;
    }

    /**
     * @param mixed $object
     *
     * @return array
     */
    public function extract($object)
    {
        if (!$object instanceof $this->className) {
            throw new \RuntimeException(
                sprintf(
                    '%s method requires object instance of %s, but got %s',
                    __FUNCTION__,
                    $this->className,
                    is_object($object) ? get_class($object) : gettype($object)
                )
            );
        }
        return $this->hydrator->extract($object);
    }

    /**
     * @param mixed $data
     *
     * @return object
     */
    public function hydrate($data)
    {
        if (!is_array($data)) {
            throw new \RuntimeException(
                sprintf(
                    'Provided data must be an array, %s given',
                    is_object($data) ? get_class($data) : gettype($data)
                )
            );
        }
        return $this->hydrator->hydrate($data, new $this->className);
    }
}