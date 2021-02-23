<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Common;

interface ClassAnalyserInterface
{
    /**
     * Analyses the constructor of a class and returns a configuration array.
     *
     * @param string $class
     *
     * @return array
     */
    public function analyse(string $class): array;
}
