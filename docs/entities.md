Entities
=========

Entities fufill the `EntityInterface` contract. For ease of use, we provide `Depotwarehouse\Blumba\Domain\Entity` as
an abstract class that implements many of the common use-cases.

Serialization
--------------

Most entities should be serializable out of the box with the `ReadModel` component. However, if the property names in the
Entity differ from the associated field mapping in the database, you will need to lend insight into how that field should
be mapped. Do this by adding a docblock to the property in the Entity, with an `@serializes` tag, denoting the database field
for that property.

For example:

```php

class MyEntity extends Entity {

    /**
    * @var string
    * @seralizes full_name
    */
    protected $name;
}
```

In the above example, the `$name` field of the Entity will map to the database column `full_name`.