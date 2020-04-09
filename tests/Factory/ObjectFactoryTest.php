<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\ObjectFactory\Tests\Factory;

use PHPUnit\Framework\TestCase;
use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Tests\MockObjects\VariadicTestClass;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;
use GrizzIt\ObjectFactory\Exception\CanNotCreateObjectException;
use GrizzIt\ObjectFactory\Exception\InvalidParameterTypeException;

/**
 * @coversDefaultClass \GrizzIt\ObjectFactory\Factory\ObjectFactory
 * @covers \GrizzIt\ObjectFactory\Exception\CanNotCreateObjectException
 * @covers \GrizzIt\ObjectFactory\Exception\InvalidParameterTypeException
 */
class ObjectFactoryTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testCreate(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));
        $this->assertInstanceOf(
            ObjectFactory::class,
            $subject
        );

        $this->assertInstanceOf(
            ObjectFactory::class,
            $subject->create(
                ObjectFactory::class,
                [
                    'classAnalyser' => [
                        'class' => ClassAnalyser::class,
                        'parameters' => [
                            'analysisStorage' => [
                                'class' => ObjectStorage::class,
                            ],
                        ],
                    ],
                ]
            )
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     */
    public function testNotInstantiableClass(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            ClassAnalyserInterface::class,
            []
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     */
    public function testNotProvidedParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            ObjectFactory::class,
            []
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testInvalidTypeParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            ObjectStorage::class,
            [
                'data' => 'foo',
            ]
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testValidTypeParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->assertInstanceOf(
            ObjectStorage::class,
            $subject->create(
                ObjectStorage::class,
                [
                    'data' => ['foo' => 'bar'],
                ]
            )
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testInvalidAggregatedInstanceParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            ObjectFactory::class,
            [
                'classAnalyser' => [
                    'class' => ClassAnalyser::class,
                    'parameters' => [],
                ],
            ]
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testInvalidConfiguredInstanceParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            ObjectFactory::class,
            [
                'classAnalyser' => 'analyser',
            ]
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testValidPassthruInstanceParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->assertInstanceOf(
            ObjectFactory::class,
            $subject->create(
                ObjectFactory::class,
                [
                    'classAnalyser' => new ClassAnalyser(new ObjectStorage()),
                ]
            )
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testVariadicParameter(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $result = $subject->create(
            VariadicTestClass::class,
            [
                'foo' => ['foo', 'bar', 'baz'],
                'bar' => 'baz',
            ]
        );

        $this->assertInstanceOf(
            VariadicTestClass::class,
            $result
        );

        $this->assertEquals(
            $result->getFoo(),
            ['foo', 'bar', 'baz']
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::parameterByType
     */
    public function testVariadicParameterFailure(): void
    {
        $subject = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));

        $this->expectException(CanNotCreateObjectException::class);

        $subject->create(
            VariadicTestClass::class,
            [
                'foo' => ['foo', 1, 'baz'],
                'bar' => 'baz',
            ]
        );
    }
}
