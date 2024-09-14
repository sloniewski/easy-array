# easy-array
Easy to use array wrapper for PHP. Adds lots of utility methods for all of your iteration needs.

## Installation

## Usage

Most method use fluent interface so you are able to chain methods on one EasyArray instance. eg:
```php
    $easyArray = new EasyArray([1,2,3,4]);
    $x = $easyArray
        ->map(function($item) { return $item * 2; })
        ->reverse()
        ->last()
    ;
    var_dump($x); // 2
```

Some methods allow to make copies of the array on the fly, you can tell the difference by naming conventions used. eg `flatten` return the current instance, and `flattend` returns a new instance of EasyArray. The same goes for `sort` and `sorted`, `fitler` and `filtered`, `reduce` and `reduced`. Although `flattend`, `sorted`, `filtered`, and `reduced` will return a new instance, the items collection is a just shallow copy, thus an array of objects will store reference to the same instances. You can force that new instance of EasyArray returned to have a cloned elements by calling EasyArray::cloneItemsOnReplicate() first. This is might make a significant difference in some cases.

## Instance public methods

### `clone(): self`

Creates a new instance of EasyArray by cloning internal array of $items. Items are cloned by recursively calling clone() on each element. The predefined clone depth is 5 levels.

Returns new instance of Easy array.

### `count()`

Get the number of element in array. Zero is returned for empty array. Returns an integer, zero is returned for empty array.

### `contains(mixed $value): bool`

Check if value is present in the array wrapper for php built-in in_array().

### `diff(): self`

Returns a new instance with values in array that are not present in the other array

### `filter(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy. Remove items form array that do no satisfy the provided filter function. 

Returns current instance of EasyArray with filtered items.

### `filtered(\Closure $closure): self`

Provided closure return values are evaluated as truthy of falsy. Instantiates a new EasyArray with elements that satisfy the provided filter function.

Returns a new instance of EasyArray with new array of filtered items.

### `first(): mixed`

Returns first element of the array

### `flatten(int $levels = 5): self`

Recursively flattens the nested arrays by modifiing the stored array. Depth of recursion is set by param levels. Does not preserve keys.

Returns current instance with flattened items.

### `flattened(int $levels = 5): self`

Recursively flatten the nested arrays, and returns new instance of EasyArray. Depth of recursion is set by param levels. Does not preserve keys.

Returns a new instance of EasyArray with flattened items.

### `get(int|string $index): mixed`

Retrieves a value stored under given index, if not found null is returned

### `getIndex(mixed $element): int|string|null`

Return index of searched element.

### `has($index): bool`

Wrapper for php build in array_key_exists_function. Checks if index exists on array.

### `(self $other): self`

Returns the array of common elements of two arrays

### `isSameAs(self $other): bool`

Checks if both arrays have the same length and store elements which are equal in the same order.

### `last(): mixed`

Fetch last element of array (unlike in pop() item ins not removed). Returns null if collection is empty.

### `includes(self $other): bool`

Checks if each element of other array is present in this array, value order is ignored.

### `includesInOrder(self $other): bool`

Checks if each element of other array is present in this array and both arrays have elements in common part in the same order.

### `intersect(self $other): self`

Wraps array_intersect, returns the array of common elements of two arrays. 

### `keyBy(\Closure $keyProvider): self`

Each element of the array will be given as argument to provided callback, and stored under key that callback provides callback that provides the key, must return something that can be used as a key.


### `keySort(?\Closure $sortFunc = null): self`

If sort function is null ksort is called to sort the array, sthis is sorts array keys in ascending order.
if sort function is given sort an array by keys using a the given comparison function.

The comparison function must return an integer less than, equal to, or greater than zero,
if the first argument is considered to be respectively less than, equal to, or greater than the second.

### `keySorted(?\Closure $sortFunc = null): self`

Works just a s keySort but instead of sorting the wrapped array, it returns a new instance with sorted elements.

### `map(\Closure $closure): self`

Applies given callback to each element of stored array

Returns new instance of EasyArray, with new collection.

### `merge(self $other): self`

Will merge two instances of EasyArray, returns new instance of EasyArray. When type checking is enabled will throw TypeError in type of values in other array do not match the stored type.

### `pop(): mixed`

Removes the last element of array and returns it

### `prepend($item): self`

Wraper for array_unshift, adds element at the begining of the array and moves everything down.

Returns current instance of EasyArray.

### `push(mixed $item): self`

Add element at the end of the array, if $strict is true (passed in constructor), will throw a TypeError if item do not match the type of other elements in the array.

Returns current instance of EasyArray.

### `purge(): self`

Removes all elements of array

### `removeFirst(): self`

Removes the first element of array.

### `removeLast(): self`

Removes the last element of array.

### `reverse(): self`

Reverse the order of stored items collection.

### `reversed(): self`

Returns new instance with reversed order of items.

### `(int $rotations): self`

If the number of rotations is positive is equivalent to successively calling $array->push($array->shift()) or if the number is negative $array->unshift($array->pop()).

### `slice(int $offset, ?int $length = null, bool $preserveKeys = false): self`

Wrapper for php array_slice built-in function. Returns a slice of an array, wrapped in EasyArray if the offset is larger than the size of the array, an empty array is returned.

### `set($index, $value): self`

Set element stored under given index to a provided value, if $strict is true (set in constructor), 
will throw a TypeError if item do not match the type of other elements in the array.

Returns current instance of EasyArray.

### `sort(?\Closure $sortFunc = null): self`

Sorts the stored array using the provided function, if not function is provided
if no sorting function is provided php build-in sort() is called (asc order)
Sorted array does not retain previous keys.
The comparison function must return an integer less than, equal to, or greater than zero,
if the first argument is considered to be respectively less than, equal to, or greater than the second.

Returns current instance with sorted items.

### `sorted(?\Closure $sortFunc = null): self`

Same as `sort` but returns new instance - clone of the array.

Returns Current instance with sorted items.

### `sumValues(): float|int`

This method will attempt to add up all elements of the array

### `(string $attribute): float|int`

This method will assume that array contains objects, it will attempt to add up given properties of the objects;

### `values()`

Wrapper for php build in array_values().

### `walk(\Closure $closure): self`

Applies callback to each element of internally stored array.

Returns current instance of EasyArray.

## Tests
Running tests

`./vendor/bin/phpunit tests/EasyArrayTest.php`





