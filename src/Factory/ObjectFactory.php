<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace GrizzIt\ObjectFactory\Factory;

use Throwable;
use GrizzIt\ObjectFactory\Common\ClassAnalyserInterface;
use GrizzIt\ObjectFactory\Common\ObjectFactoryInterface;
use GrizzIt\ObjectFactory\Exception\MissingParameterException;
use GrizzIt\ObjectFactory\Exception\CanNotCreateObjectException;
use GrizzIt\ObjectFactory\Exception\InvalidParameterTypeException;

class ObjectFactory implements ObjectFactoryInterface
{
    /**
     * Contains the class analyser.
     *
     * @var ClassAnalyserInterface
     */
    private $classAnalyser;

    /**
     * Constructor.
     *
     * @param ClassAnalyserInterface $classAnalyser
     */
    public function __construct(ClassAnalyserInterface $classAnalyser)
    {
        $this->classAnalyser = $classAnalyser;
    }

    /**
     * Creates an instance of an object.
     *
     * @param string $class
     * @param array $parameters
     *
     * @return object
     *
     * @throws CanNotCreateObjectException When the object can not be created.
     */
    public function create(string $class, array $parameters): object
    {
        try {
            $config = $this->classAnalyser->analyse($class);
        } catch (Throwable $exception) {
            throw new CanNotCreateObjectException(
                $class,
                [],
                $parameters,
                $exception
            );
        }

        try {
            $invokeParameters = $this->translate(
                $config,
                $parameters
            );
        } catch (Throwable $exception) {
            throw new CanNotCreateObjectException(
                $class,
                $config,
                $parameters,
                $exception
            );
        }


        return new $class(...$invokeParameters);
    }

    /**
     * Converts the parameter order based on the configuration and returns a
     * array which can be passed variadically.
     *
     * @return array
     */
    private function translate(array $config, array $parameters): array
    {
        $invokeParameters = [];
        foreach ($config as $parameterName => $parameterConfig) {
            if (!isset($parameters[$parameterName])) {
                if (
                    !$parameterConfig['allowsNull']
                    && !$parameterConfig['isOptional']
                ) {
                    throw new MissingParameterException($parameterConfig);
                }

                $invokeParameters[] = $parameterConfig['default'];

                continue;
            }

            if ($parameterConfig['isVariadic']) {
                foreach ($parameters[$parameterName] as $variadicParameter) {
                    $invokeParameters[] = $this->parameterByType(
                        $parameterConfig,
                        $variadicParameter
                    );
                }

                continue;
            }

            $invokeParameters[] = $this->parameterByType(
                $parameterConfig,
                $parameters[$parameterName]
            );
        }

        return $invokeParameters;
    }

        /**
     * Checks the parameter type.
     *
     * @param array $parameterConfig
     * @param mixed $parameter
     *
     * @return mixed
     *
     * @throws InvalidParameterTypeException When the type can not be resolved.
     */
    private function parameterByType(array $parameterConfig, $parameter)
    {
        if ($parameterConfig['builtin']) {
            if (
                gettype(
                    $parameter
                ) === $parameterConfig['type']
                || $parameterConfig['type'] === 'mixed'
            ) {
                return $parameter;
            }

            throw new InvalidParameterTypeException(
                $parameterConfig,
                $parameter
            );
        }

        if (
            !is_a(
                $parameter,
                $parameterConfig['type']
            )
        ) {
            if (is_array($parameter)) {
                try {
                    return $this->create(
                        $parameter['class'],
                        $parameter['parameters'] ?? []
                    );
                } catch (Throwable $exception) {
                    throw new InvalidParameterTypeException(
                        $parameterConfig,
                        $parameter,
                        $exception
                    );
                }
            }

            throw new InvalidParameterTypeException(
                $parameterConfig,
                $parameter
            );
        }

        return $parameter;
    }
}
