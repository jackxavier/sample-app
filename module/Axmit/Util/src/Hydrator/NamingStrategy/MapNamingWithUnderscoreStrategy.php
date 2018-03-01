<?php

namespace Axmit\Util\Hydrator\NamingStrategy;

use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
USE Zend\Hydrator\NamingStrategy\MapNamingStrategy;

/**
 * Class MapNamingWithUnderscoreStrategy
 *
 * @package Axmit\Util\Hydrator\NamingStrategy
 */
class MapNamingWithUnderscoreStrategy extends MapNamingStrategy
{
    /**
     * @var bool
     */
    protected $underscoreSeparatedKeys = true;

    /**
     * @var UnderscoreNamingStrategy
     */
    protected $underscoreNamingStrategy;

    /**
     * MapNamingWithUnderscoreStrategy constructor.
     *
     * @param array      $mapping
     * @param array|null $reverse
     * @param bool       $underscoreSeparatedKeys
     */
    public function __construct(array $mapping, array $reverse = null, $underscoreSeparatedKeys = true)
    {
        parent::__construct($mapping, $reverse);
        $this->underscoreNamingStrategy = new UnderscoreNamingStrategy();
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     */
    public function hydrate($name)
    {
        $name = parent::hydrate($name);

        if ($this->underscoreSeparatedKeys) {
            $name = $this->underscoreNamingStrategy->hydrate($name);
        }

        return $name;
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     */
    public function extract($name)
    {
        $name = parent::extract($name);

        if ($this->underscoreSeparatedKeys) {
            $name = $this->underscoreNamingStrategy->extract($name);
        }

        return $name;
    }
}
