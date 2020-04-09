<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\ObjectFactory\Tests\Component\Analyser;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;
use GrizzIt\ObjectFactory\Exception\NonInstantiableClassException;

/**
 * @coversDefaultClass \GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser
 * @covers \GrizzIt\ObjectFactory\Exception\NonInstantiableClassException
 */
class ClassAnalyserTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::analyse
     * @covers ::reflect
     */
    public function testAnalyse(): void
    {
        $subject = new ClassAnalyser(new ObjectStorage());

        $this->assertInstanceOf(ClassAnalyser::class, $subject);

        $result = $subject->analyse(ObjectStorage::class);

        $this->assertEquals([
            'data' => [
                'type' => 'array',
                'builtin' => true,
                'allowsNull' => false,
                'isOptional' => true,
                'isVariadic' => false,
                'default' => [],
            ]
        ], $result);

        $this->expectException(NonInstantiableClassException::class);

        $subject->analyse(ClassAnalyserInterface::class);
    }
}
