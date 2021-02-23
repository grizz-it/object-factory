<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Component\Reflector;

use ReflectionClass;
use GrizzIt\Storage\Common\StorageInterface;
use GrizzIt\ObjectFactory\Common\MethodReflectorInterface;

class MethodReflector implements MethodReflectorInterface
{
    /**
     * A translation array to equalize the expected types of the gettype method.
     *
     * @var array
     */
    private array $translations = [
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
    private StorageInterface $analysisStorage;

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
     * Analyses a method of a class and returns a configuration array.
     *
     * @param string $class
     * @param string $method
     *
     * @return array
     */
    public function reflect(string $class, string $method): array
    {
        $reference = sprintf('%s::%s', $class, $method);
        if (!$this->analysisStorage->has($reference)) {
            $reflection = new ReflectionClass($class);

            $reflectionMethod = $method === '__construct'
                ? $reflection->getConstructor()
                : $reflection->getMethod($method);

            $parameters = [];
            if ($reflectionMethod !== null) {
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    $parameters[$parameter->getName()] = [
                        'type' => $type !== null ?
                            (array_key_exists($type->getName(), $this->translations)
                                ? $this->translations[$type->getName()]
                                : $type->getName())
                            : 'mixed',
                        'builtin' => $type !== null ? $type->isBuiltin() : true,
                        'allowsNull' => $parameter->allowsNull(),
                        'isOptional' => $parameter->isOptional(),
                        'isVariadic' => $parameter->isVariadic(),
                        'default' => $parameter->isDefaultValueAvailable()
                            ? $parameter->getDefaultValue()
                            : null,
                    ];
                }
            }

            $this->analysisStorage->set($reference, $parameters);
        }

        return $this->analysisStorage->get($reference);
    }
}
