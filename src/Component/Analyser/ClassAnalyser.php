<?php
/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace GrizzIt\ObjectFactory\Component\Analyser;

use ReflectionClass;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Exception\NonInstantiableClassException;

class ClassAnalyser implements ClassAnalyserInterface
{
    /**
     * A translation array to equalize the expected types of the gettype method.
     *
     * @var array
     */
    private $translations = [
        'bool' => 'boolean',
        'int' => 'integer',
        'float' => 'double',
        'callable' => 'array',
    ];

    /**
     * Contains the previously analysed classes.
     *
     * @var StorageInterface
     */
    private $analysisStorage;

    /**
     * Constructor
     *
     * @param StorageInterface $analysisStorage
     */
    public function __construct(StorageInterface $analysisStorage)
    {
        $this->analysisStorage = $analysisStorage;
    }

    /**
     * Analyses the constructor of a class and returns a configuration array.
     *
     * @return array
     *
     * @throws NonInstantiableClassException When the analysed class is not instantiable.
     */
    public function analyse(string $class): array
    {
        if (!$this->analysisStorage->has($class)) {
            $this->analysisStorage->set(
                $class,
                $this->reflect($class)
            );
        }

        return $this->analysisStorage->get($class);
    }

    /**
     * Retrieves a class analysis.
     *
     * @param string $class
     *
     * @return array
     *
     * @throws NonInstantiableClassException When the analysed class is not instantiable.
     */
    private function reflect(string $class): array
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->isInstantiable()) {
            $constructor = $reflection->getConstructor();
            $parameters = [];

            if ($constructor !== null) {
                foreach ($constructor->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    $parameters[$parameter->getName()] = [
                        'type' => $type !== null ?
                            (array_key_exists($type->getName(), $this->translations)
                                ? $this->translations[$type->getName()]
                                : $type->getName())
                            : 'mixed',
                        'builtin' => $type !== null ? $type->isBuiltin(): true,
                        'allowsNull' => $parameter->allowsNull(),
                        'isOptional' => $parameter->isOptional(),
                        'isVariadic' => $parameter->isVariadic(),
                        'default' => $parameter->isDefaultValueAvailable()
                            ? $parameter->getDefaultValue()
                            : null,
                    ];
                }
            }

            return $parameters;
        }

        throw new NonInstantiableClassException(
            $class
        );
    }
}
