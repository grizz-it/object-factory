<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Exception;

use Exception;
use Throwable;

class MissingParameterException extends Exception
{
    /**
     * Constructor.
     *
     * @param array $parameterConfig
     * @param Throwable $previous
     */
    public function __construct(
        array $parameterConfig,
        Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Parameter missing for %s.',
                print_r($parameterConfig, true)
            ),
            0,
            $previous
        );
    }
}
