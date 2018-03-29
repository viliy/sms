<?php

namespace Viliy\SMS\Exceptions;

use Throwable;

/**
 * Class Exception
 * @package Viliy\SMS
 */
class NoGatewayAvailableException extends Exception
{
    /**
     * @var array
     */
    public $results = [];

    /**
     * NoGatewayAvailableException constructor.
     *
     * @param array           $results
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $results = [], $code = 0, Throwable $previous = null)
    {
        $this->results = $results;
        parent::__construct('All the gateways have failed.', $code, $previous);
    }
}
