<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Tests\MockObjects;

class TranslatableClass
{
    /**
     * Retrieves foo.
     *
     * @return int
     */
    public function getFoo(int $input): int
    {
        return $input;
    }
}
