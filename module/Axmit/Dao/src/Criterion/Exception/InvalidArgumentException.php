<?php
/**
 * File contains Class InvalidArgumentException
 *
 * @since  21.01.2016
 * @author Eduard Posinitskii <eduard.posinitskii@veeam.com>
 */

namespace Axmit\Dao\Criterion\Exception;

use InvalidArgumentException as BaseException;

/**
 * Class InvalidArgumentException
 *
 * @package Axmit\Dao\Criterion\Exception
 * @author  Eduard Posinitskii <eduard.posinitskii@veeam.com>
 */
class InvalidArgumentException extends BaseException
{
    public static function criteriaIsNotDefined($paramName)
    {
        return new static(sprintf('Criteria for %s is not defined', $paramName));
    }
}
