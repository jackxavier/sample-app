<?php

namespace Zsa\Pip\Dto\PipAttach;

/**
 * Class PipAttachTo
 *
 * @package Zsa\Pip\Dto\PipAttach
 */
class PipAttachTo
{
    const ACTION_PIP_ATTACH = 'attach';
    const ACTION_PIP_DETACH = 'detach';

    /**
     * @var mixed
     */
    protected $pip = null;

    /**
     * @var mixed
     */
    protected $service = null;

    /**
     * @var mixed
     */
    protected $related = null;

    /**
     * @var mixed
     */
    protected $pipAction = self::ACTION_PIP_ATTACH;

    /**
     * @var string
     */
    protected $relationType = null;

    /**
     * @return mixed
     */
    public function getPip()
    {
        return $this->pip;
    }

    /**
     * @param mixed $pip
     *
     * @return PipAttachTo
     */
    public function setPip($pip = null)
    {
        $this->pip = $pip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     *
     * @return PipAttachTo
     */
    public function setService($service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @param mixed $related
     *
     * @return PipAttachTo
     */
    public function setRelated($related = null)
    {
        $this->related = $related;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPipAction()
    {
        return $this->pipAction;
    }

    /**
     * @param mixed $pipAction
     *
     * @return PipAttachTo
     */
    public function setPipAction($pipAction = null)
    {
        $this->pipAction = $pipAction;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * @param string $relationType
     *
     * @return PipAttachTo
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }
}
