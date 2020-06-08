<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Tests\Component\Analyser;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Component\Reflector\MethodReflector;
use GrizzIt\ObjectFactory\Tests\MockObjects\TranslatableClass;

/**
 * @coversDefaultClass \GrizzIt\ObjectFactory\Component\Reflector\MethodReflector
 */
class MethodReflectorTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::reflect
     */
    public function testAnalyse(): void
    {
        $subject = new MethodReflector(new ObjectStorage());

        $result = $subject->reflect(TranslatableClass::class, 'getFoo');

        $this->assertEquals([
            'input' => [
                'type' => 'integer',
                'builtin' => true,
                'allowsNull' => false,
                'isOptional' => false,
                'isVariadic' => false,
                'default' => null,
            ]
        ], $result);
    }
}
