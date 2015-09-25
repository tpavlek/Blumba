Value Objects
==============

Value Objects represent values in the system. They either implement the `ValueObjectInterface`, or extend the
default `ValueObject` class.

Numeric Values
---------------

Some Value Objects are "string" or "data" based, for example `FirstName` or `Address`. However, some value objects
are Numeric based: `Price`, `Duration`, etc. These numeric based objects can implement the NumericValueInterface and
`use` the `NumericValue` trait. This trait provides a default implementation for the method `sum(NumericValueInterface ...$values)`.

`sum` takes a variadic list of `NumericValueInterface` arguments and returns a new one of the same type as the caller
representing the sum of all the arguments passed.
