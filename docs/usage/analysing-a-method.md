# GrizzIT Object Factory - Analysing a method

The package allows the inspection of a method. This can be done through the
[MethodReflector](../../src/Component/Reflector/MethodReflector.php). The
reflector will provide an array of configuration which can be used in the
ObjectFactory and the InvocationTranslator to perform actions.

To create a new instance of the reflector, use the following snippet:
```php
<?php

use GrizzIt\Storage\Component\ObjectStorage;
use GrizzIt\ObjectFactory\Component\Reflector\MethodReflector;

$analyser = new MethodReflector(new ObjectStorage());
```

In order to retrieve the configuration for a method, use the following snippet:
```php
<?php

$methodReflector->analyse(ObjectStorage::class, 'set');

```

This will return a similar array to the one received from the class analyser,
but now for a specific method.

## Further reading

[Back to usage index](index.md)

[Analysing a class](analysing-a-class.md)

[Creating objects](creating-objects.md)
