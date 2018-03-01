<?php

namespace Application\Controller\Plugin;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Session\Container;

/**
 * Class ReturnUri
 *
 * @package Application\Controller\Plugin
 * @author  ma4eto <eddiespb@gmail.com>
 */
class ReturnUri extends AbstractPlugin
{

    const RETURN_URI_KEY = 'return_uri';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $returnUriQueryParam;

    /**
     * ReturnUri constructor.
     *
     * @param Container $container
     * @param string    $returnUriQueryParam
     */
    public function __construct(Container $container, $returnUriQueryParam = 'returnUri')
    {
        $this->container           = $container;
        $this->returnUriQueryParam = (string)$returnUriQueryParam;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function hasRedirect()
    {
        return $this->container->offsetExists(static::RETURN_URI_KEY);
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function fromQuery(Request $request)
    {
        if (!$uri = $request->getQuery($this->returnUriQueryParam)) {
            return $this;
        }

        return $this->setReturnUri($uri);
    }

    /**
     * @param string $uri
     *
     * @return $this
     */
    public function setReturnUri($uri)
    {
        $this->store($uri);

        return $this;
    }

    /**
     * @return Response
     */
    public function redirect()
    {
        if (!$this->hasRedirect()) {
            throw new \RuntimeException(
                sprintf('Return URI is not defined. Use %s::%s to check for existence', __CLASS__, 'hasRedirect()')
            );
        }

        /** @var Redirect $plugin */
        $plugin = $this->controller->plugin('redirect');
        $uri    = $this->pop();

        return $plugin->toUrl($uri);
    }

    /**
     * Clears stored URI
     *
     * @return void
     */
    public function clear()
    {
        $this->pop();
    }

    /**
     * @return string
     */
    protected function pop()
    {
        $uri = $this->container->offsetGet(static::RETURN_URI_KEY);

        $this->container->offsetUnset(static::RETURN_URI_KEY);

        return $uri;
    }

    /**
     * @param string $uri
     *
     * @return $this
     */
    protected function store($uri)
    {
        $this->container->offsetSet(static::RETURN_URI_KEY, $uri);

        return $this;
    }
}
