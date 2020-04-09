<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Exception;

use Exception;

class NonInstantiableClassException extends Exception
{
    /**
     * Constructor.
     *
     * @param array $parameterConfig
     * @param mixed $parameter
     * @param Throwable $previous
     */
    public function __construct(
        string $class
    ) {
        parent::__construct(
            sprintf(
                'Tried to analyse constructor of not instantiable class: %s.',
                $class
            )
        );
    }
}
