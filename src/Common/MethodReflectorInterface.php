<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Common;

interface MethodReflectorInterface
{
    /**
     * Analyses a method of a class and returns a configuration array.
     *
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    public function reflect(string $class, string $method): array;
}
