<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Exception;

use Exception;
use Throwable;

class CanNotCreateObjectException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $class
     * @param array $parameterConfig
     * @param array $parameters
     * @param Throwable $previous
     */
    public function __construct(
        string $class,
        array $parameterConfig,
        array $parameters,
        Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Can not create class %s, with configuration %s, received %s.',
                $class,
                print_r($parameterConfig, true),
                print_r($parameters, true)
            ),
            0,
            $previous
        );
    }
}
