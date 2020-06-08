# GrizzIT Object Factory - Creating objects

Creating object can be done through the
[ObjectFactory](../../src/Factory/ObjectFactory.php) provided in the package.
It has one public method `create` which accepts a FQCN and the parameters for
the provided class. It can then instantiate the class. The class uses the
class analyser to determine the structure of the constructor parameters.

Creating an ObjectFactory can simply be done with the following snippet:
```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;

$factory = new ObjectFactory(new ClassAnalyser(new ObjectStorage()));
```

To create an object with the factory, simply pass the class and parameters to
the create method.

```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;

/** @var ObjectStorage $result */
$result = $factory->create(
    ObjectStorage::class,
    [
        'data' => ['foo']
    ]
);
```

A key-value structure is used for the parameters provided to the ObjectFactory.
If (in this case the ObjectStorage class) expects a `$data` parameter of the
type array in the `__construct` method, then the structure of the `$parameter`
parameter will be as follows:
```php
$parameters = [
    'data' => [/** Value of $data here. */]
];
```

For variadic parameters, this structure is the same.

## Object nesting

Some objects require other objects in their `__construct` method. With the
ObjectFactory it is also possible to create these object, with the correct
configuration.

There are two ways to create the objects.

### Configuration declaration

It is possible to completely configure the nested objects, expected by the method.
Instead of passing along the variable as is, a array is used with one expected
and one optional node. The expected node is `class`, this array node should contain
the string representation of the expected class. The optional node is `parameters`,
this array node will contain the objects' instantiation parameters. If none are
required, then this can be left empty or undeclared.

To create the ObjectFactory with a (full) configuration declaration, would look
like this:
```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;

/** @var ObjectFactory $result */
$result = $factory->create(
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
);
```

The configuration declaration could technically be infinitely deep.

### Object declaration

It is also possible to re-use a previously generated or instantiation instance
of a class. This can be done, by simply passing along the object in the parameters.

```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Factory\ObjectFactory;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;

$classAnalyser = new ClassAnalyser(new ObjectStorage());

/** @var ObjectFactory $result */
$result = $factory->create(
    ObjectFactory::class,
    [
        'classAnalyser' => $classAnalyser
    ]
);
```

Both of these declaration methods can be used and mixed throughout the declaration.

## Further reading

[Back to usage index](index.md)

[Analysing a class](analysing-a-class.md)

[Analysing a method](analysing-a-method.md)
