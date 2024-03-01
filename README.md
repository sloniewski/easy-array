# easy-array
Easy to use array wrapper for PHP. EasyArray constructor accepts additional param 'strict', if set to true all values added to the array with methods push(), set(), merge() and offesetSet() will be checked in they match type of other elements existing on the internally stored array. 

Instantiating EasyArray with 'strict' set to true, and array of elements of different types will result in TypeError.

When using search functions such as contains() or includes() no iteration on internally stored array will be done if type of provided param does not match the type stored on EasyArray.

## Installation

## Instance public methods

#### ` __construct(array<mixed> $items[, bool $strict = false ])`

When $strict is true, constructor will throw a TypeError if all items in $items are not the same type

Setting $strict to true, will make methods push(), set(), merge() throw a TypeError, when argument of diffrent type the elements stored in $items


#### `clone(): self`

Recursively clones array $items calling clone() on each of its items

##### Return value

New instance

#### `map(\Closure $closure): self`

Applies given callback to each element of stored array

If instance has strict set to true, new instance will have type infered from first call of provided closure, all subsequent items are expected to have the same type. TypeError will be thrown if types of next items do not match the type of first item


##### Return value

Returns new instance of EasyArray, with new collection.

#### `walk(\Closure $closure): self`

Applies callback to each element of internally stored array

TypeError is thrown when strinct is true, and type returned by provided closure does not match the type set on array. When TypeError is thrown, the internally stored array remains unmodified.

##### Return value

Current instance of EasyArray

#### `find(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy.
Returns the first item that satisfies the provided testing function.
If no values satisfy the testing function, null is returned. 

##### Return value

Item found on array or null

#### `findIndex(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy.
Returns index of the first item that satisfies the provided closure.
If no values satisfy the testing function, null is returned. 

##### Return value

Index found on array or null
