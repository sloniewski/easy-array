# easy-array
Easy to use array wrapper for PHP. Adds lots of utility methods for all of your iteration needs.


EasyArray constructor accepts additional param `$strict`, if set to true all values added to the array with methods `push()`, `set()`, `merge()` and `offesetSet()` will be checked in they match type of other elements existing on the internally stored array. Instantiating EasyArray with 'strict' set to true and array of elements of different types will result in TypeError.

When using search functions such as contains() or includes() no iteration will be done if type of provided param does not match the type stored on EasyArray.

## Installation

## Instance public methods

#### ` __construct(array<mixed> $items[, bool $strict = false ])`

When $strict is true, constructor will throw a TypeError if all items in $items are not the same type

Setting $strict to true, will make methods push(), set(), merge() throw a TypeError, when argument of diffrent type the elements stored in $items


#### `clone(): self`

Creates a new instance of EasyArray by cloning internal array of $items. Items are cloned by recursively calling clone() on each element. The predefined clone depth is 5 levels.

##### Return value

New instance


#### `isTyped(): bool`

Getter for "strinc" property.

##### Return value

boolean

#### `pop(): mixed`

Removes the last element of array and returns it

##### Return value

mixed - last element of array


#### `removeLast(): self`

Removes the last element of array

##### Return value

self - current instance of EasyArray


#### `push($item): self`

Add element at the end of the array, if $strict is true (passed in constructor), will throw a TypeError if item do not match the type of other elements in the array.

##### Return value

self - current instance of EasyArray

#### `set($index, $value): self`

Set element stored under given index to a provided value, if $strict is true (set in constructor), 
will throw a TypeError if item do not match the type of other elements in the array.

##### Return value

self - current instance of EasyArray


#### `count()`

Get the number of element in array. Zero is returned for empty array.

##### Return value

integer


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


#### `filter(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy.
Remove items form array that do no satisfy the provided filter function.

##### Return value

Current of EasyArray with filtered items.


#### `filtered(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy.
Instantiates a new EasyArray with elements that satisfy the provided filter function.

##### Return value

New instance of EasyArray with new array of filtered items.


#### `flatten(int $levels = 5): self`

Recursively flattens the nested arrays by modifiing the stored array. Depth of recursion is set by param levels.
Does not preserve keys.

##### Return value

Current instance with flattened items.


#### `flattened(int $levels = 5): self`

Recursively flatten the nested arrays, and returns new instance of EasyArray. Depth of recursion is set by param levels.
Does not preserve keys.

##### Return value

New instance of EasyArray with flattened items.



