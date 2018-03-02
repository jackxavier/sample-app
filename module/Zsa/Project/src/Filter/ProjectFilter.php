<?php

namespace Zsa\Project\Filter;

use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;
use Zsa\Project\Entity\Project;
use Zsa\Project\Transformer\ProjectTransformer;

/**
 * Class ProjectFilter
 *
 * @package Zsa\Project\Filter
 */
class ProjectFilter implements FilterInterface
{
    const VALUE_MODE_MINIMIZED = 'minimized';
    const VALUE_MODE_VIEW      = 'view';
    const VALUE_MODE_WEB_VIEW  = 'web_view';

    protected $mode = self::VALUE_MODE_VIEW;

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (!$value instanceof Project) {
            return $value;
        }

        switch ($this->mode) {
            case self::VALUE_MODE_MINIMIZED:
                return ProjectTransformer::toProjectTo($value);
                break;
            case self::VALUE_MODE_WEB_VIEW:
                return ProjectTransformer::toProjectWebViewTo($value);
                break;
            default:
                return ProjectTransformer::toProjectViewTo($value);
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
     * @return ProjectFilter
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }
}