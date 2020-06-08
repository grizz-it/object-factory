<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Common;

interface ObjectFactoryInterface
{
    /**
     * Creates an instance of an object.
     *
     * @return object
     */
    public function create(string $class, array $parameters): object;
}
