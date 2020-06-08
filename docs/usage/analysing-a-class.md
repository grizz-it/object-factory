# GrizzIT Object Factory - Analysing a class

The package provides an
[Analyser](../../src/Component/Analyser/ClassAnalyser.php) class, which
retrieves an instantiation signature of a class. This analyser is used by the
[ObjectFactory](../../src/Factory/ObjectFactory.php) to determine the order of
the provided parameter from the configuration. The analyser expects an
implementation of the StorageInterface from the `grizz-it/storage` package.
This implementation can be used to store previous analyses and retrieve them
at a later point (e.g. a caching mechanisms).

The class analyser can be instantiated using the following snippet:
```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Component\Analyser\ClassAnalyser;

$analyser = new ClassAnalyser(new ObjectStorage());
```

A class can then be analysed by invoking `analyse` with a FQCN:
```php

use GrizzIt\Storage\Component\ObjectStorage;

$analyser->analyse(ObjectStorage::class);
```

This will return an array which can be used to programmatically determine how a
class should be instantiated.

## Further reading

[Back to usage index](index.md)

[Analysing a method](analysing-a-method.md)

[Creating objects](creating-objects.md)
