<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Exception;

use Exception;
use Throwable;

class InvalidParameterTypeException extends Exception
{
    /**
     * Constructor.
     *
     * @param array $parameterConfig
     * @param mixed $parameter
     * @param Throwable $previous
     */
    public function __construct(
        array $parameterConfig,
        $parameter,
        Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Invalid parameter type provided, expected to fulfill %s, but got %s.',
                print_r($parameterConfig, true),
                print_r($parameter, true)
            ),
            0,
            $previous
        );
    }
}
