<?php

namespace Axmit\Util\Hydrator\Strategy;

use Doctrine\Common\Persistence\ObjectRepository;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class DoctrineObject
 *
 * @package Axmit\Util\Hydrator\Strategy
 */
class DoctrineObject implements StrategyInterface
{

    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * @var string
     */
    protected $identifier;

    public function __construct(ObjectRepository $repository, $identifier)
    {
        $this->objectRepository = $repository;
        $this->identifier       = $identifier;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed  $value  The original value.
     * @param object $object (optional) The original object for context.
     *
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if (!is_object($value)) {
            return $value;
        }

        $getter = 'get' . ucfirst($this->identifier);
        if (!method_exists($value, $getter)) {
            return $value;
        }

        return call_user_func([$value, $getter]);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @param array $data  (optional) The original data for context.
     *
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (!$value || is_object($value)) {
            return $value;
        }

        $matched = $this->objectRepository->findOneBy([$this->identifier => $value]);
        if (!$matched) {
            return null;
        }

        return $matched;
    }

}
