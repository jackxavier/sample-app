<?php

namespace Zsa\Pip\Filter;

use Zsa\Pip\Entity\Pip;
use Zsa\Pip\Transformer\PipTransformer;
use Zend\Filter\FilterInterface;

/**
 * Class PipFilter
 *
 * @package Zsa\Pip\Filter
 */
class PipFilter implements FilterInterface
{
    const VALUE_MODE_MINIMIZED = 'minimized';
    const VALUE_MODE_VIEW      = 'view';
    const VALUE_MODE_WEB_VIEW  = 'web_view';

    /**
     * @var string
     */
    protected $mode = self::VALUE_MODE_WEB_VIEW;

    /**
     * @param mixed $value
     *
     * @return \Zsa\Pip\Dto\Pip\PipEditTo|mixed
     */
    public function filter($value)
    {
        if (!$value instanceof Pip) {
            return $value;
        }

        switch ($this->mode) {
            case self::VALUE_MODE_MINIMIZED:
                return PipTransformer::toPipTo($value);
                break;
            case self::VALUE_MODE_VIEW:
                return PipTransformer::toPipViewTo($value);
                break;
            default:
                return PipTransformer::toPipWebViewTo($value);
        }
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     *
     * @return PipFilter
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }
}