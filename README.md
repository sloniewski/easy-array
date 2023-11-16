# easy-array
Easy to use array wrapper for PHP 

## Installation

## Instance public methods

#### `public __construct(array<mixed> $items[, bool $strict = false ])`

When $strict is true, constructor will throw a TypeError if all items in $items are not the same type

Setting $strict to true, will make methods push(), set(), merge() throw a TypeError, when argument of diffrent type the elements stored in $items

##### Parameters
* $items : array<string|int, mixed>
* $strict : bool = false

#### `public clone(): self`

Recursively clones array $items calling clone() on each of its items

##### Return value
self

