Entities
=========

Entities fufill the `EntityInterface` contract. For ease of use, we provide `Depotwarehouse\Blumba\Domain\Entity` as
an abstract class that implements many of the common use-cases.

Property Convention
--------------------

The convention established by entities is that their properties will be `snake_cased`, with the getters being `getCamelCased()`.

Any properties that are simple booleans such as `is_active` should be named as a property just like so. The get method
for such a property would be phrased like a question `$entity->isActive()`.

These conventions are important as they are assumed by the `ReadModelRepository` for quick serialization of `Entities`.

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
