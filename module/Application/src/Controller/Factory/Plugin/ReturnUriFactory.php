<?php

namespace Application\Controller\Factory\Plugin;

use Application\Controller\Plugin\ReturnUri;
use Interop\Container\ContainerInterface;
use Zend\Session\Container;

/**
 * Class ReturnUriFactory
 *
 * @package Application\Controller\Factory\Plugin
 * @author  ma4eto <eddiespb@gmail.com>
 */
class ReturnUriFactory
{

    public function __invoke(ContainerInterface $container)
    {
        return new ReturnUri(new Container('sessionReturnUri'));
    }
}
