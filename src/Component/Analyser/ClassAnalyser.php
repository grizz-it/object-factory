<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Component\Analyser;

use ReflectionClass;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\MethodReflectorInterface;
use GrizzIt\ObjectFactory\Component\Reflector\MethodReflector;
use GrizzIt\ObjectFactory\Exception\NonInstantiableClassException;

class ClassAnalyser implements ClassAnalyserInterface
{
    /**
     * Contains the previously analysed classes.
     *
     * @var StorageInterface
     */
    private StorageInterface $analysisStorage;

    /**
     * Contains the method reflector.
     *
     * @var MethodReflectorInterface
     */
    private MethodReflectorInterface $methodReflector;

    /**
     * Constructor
     *
     * @param StorageInterface $analysisStorage
     * @param MethodReflectorInterface $methodReflector
     */
    public function __construct(
        StorageInterface $analysisStorage,
        MethodReflectorInterface $methodReflector = null
    ) {
        $this->analysisStorage = $analysisStorage;
        $this->methodReflector = $methodReflector ?? new MethodReflector(
            $analysisStorage
        );
    }

    /**
     * Analyses the constructor of a class and returns a configuration array.
     *
     * @param string $class
     *
     * @return array
     *
     * @throws NonInstantiableClassException When the analysed class is not instantiable.
     */
    public function analyse(string $class): array
    {
        if (!$this->analysisStorage->has($class)) {
            $reflection = new ReflectionClass($class);

            if (!$reflection->isInstantiable()) {
                throw new NonInstantiableClassException(
                    $class
                );
            }

            $this->analysisStorage->set(
                $class,
                $this->methodReflector->reflect($class, '__construct')
            );
        }

        return $this->analysisStorage->get($class);
    }
}
