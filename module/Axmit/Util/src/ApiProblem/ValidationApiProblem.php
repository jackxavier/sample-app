<?php

namespace Axmit\Util\ApiProblem;

use ZF\ApiProblem\ApiProblem;

/**
 * Class ValidationApiProblem
 *
 * @package Axmit\Util\ApiProblem
 */
class ValidationApiProblem extends ApiProblem
{
    /**
     * Constructor.
     *
     * Create an instance using the provided information. If nothing is
     * provided for the type field, the class default will be used;
     * if the status matches any known, the title field will be selected
     * from $problemStatusTitles as a result.
     *
     * @param array $additional
     */
    public function __construct(array $additional)
    {
        parent::__construct(422, 'Failed Validation', null, null, ['validation_messages' => $additional]);
    }

}
