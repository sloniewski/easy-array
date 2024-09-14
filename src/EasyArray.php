<?php
declare(strict_types=1);

namespace EasyArray;

use EasyArray\Cloner\Cloner;
use EasyArray\ArrayUtils\ArrayUtils;

class EasyArray implements \ArrayAccess, \Iterator, \Countable
{
    public const LESS = -1;
    public const GREATER = 1;
    public const EQUALS = 0;

    private array $items = [];
    private Cloner $cloner;
    private ArrayUtils $arrayUtils;
    private bool $cloneItemsOnReplicate = false;

    /**
     * @param array $items
     */
    public function __construct(
        array $items,
    ){
        $this->cloner = new Cloner();
        $this->arrayUtils = new ArrayUtils;

        $this->items = $items;
        $this->position = 0;
    }

    public function __clone() 
    {
        $this->items = $this->cloner->clone($this->items);
    }

    /**
     * Creates a new instance of EasyArray by cloning internal array of $items. 
     * Items are cloned by recursively calling clone() on each element. 
     * The predefined clone depth is 5 levels.
     *
     * @return self
     */
    public function clone()
    {
        return clone($this);
    }

    /**
     * Get the number of items in array
     * 
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return self
     */
    public function diff(self $other): self
    {
        return $this->replicateSelfWith(
            array_diff($this->items, $other->items)
        );
    }

    /**
     * Remove items form array that do no satisfy the provided filter function.
    * @param \Closure $callback
    * @return self
    */
    public function filter(\Closure $callback): self
    {
        $this->items = $this->arrayUtils->filterArray($callback, $this->items);

        return $this;
    }
  
    /**
     * Remove items form array that do no satisfy the provided filter function.
     *
     * @param \Closure $callback
     * @return self
    */
    public function filtered(\Closure $callback): self
    {
        return $this->replicateSelfWith(
            $this->arrayUtils->filterArray($callback, $this->items)
        );
    }

    /**
     * Fetch first element of array
     * Returns null if $items is empty
     * 
     * @return mixed|null
     */
    public function first(): mixed
    {
        if(count($this->items) == 0) {
            return null;
        }

        return array_values($this->items)[0];
    }

    /**
     * Flattens the nested arrays, does not preserve keys
     *
     * @param int $levels
     * @return self
     */
    public function flattened(int $levels = 5): self
    {
        $flat = $this->arrayUtils->flattenArray($this->items, $levels);

        return $this->replicateSelfWith($flat);
    }

    /**
     * Flattens the nested arrays by modifying the stored array. Does not preserve keys.
     *
     * @return self
     */
    public function flatten(int $levels = 5): self
    {
        $this->items = $this->arrayUtils->flattenArray($this->items, $levels);

        return $this;
    }

    public function cloneItemsOnReplicate(): self
    {
        $this->cloneItemsOnReplicate = true;

        return $this;
    }

    public function dontCloneItemsOnReplicate(): self
    {
        $this->cloneItemsOnReplicate = false;

        return $this;
    }

    /**
     * Wrapper for php build in array_pop
     * @return mixed
     */
    public function pop(): mixed
    {
        return array_pop($this->items);
    }

    /**
     * Removes the last element of the array, thus shortening it
     * 
     * @return self
     */
    public function removeLast(): self
    {
        $this->pop();

        return $this;
    }

    /**
     * Add element at the end of the array.
     * 
     * @param mixed $item
     * @return self
     */
    public function push(mixed $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Set element stored under given index to a provided value.
     *
     * @param int|string $index
     * @param mixed $value
     * @return self
     */
    public function set(int|string $index, mixed $value): self
    {
        $this->items[$index] = $value;
        
        return $this;
    }

    /**
     * Apply callback to each element of stored array
     * returns new instance of EasyArray
     * 
     * @param \Closure $closure
     * @return self
     */
    public function map(\Closure $closure): self
    {
        $mapped = $this->replicateSelfWith([]);

        foreach($this->items as $key => $item) {
            $newItem = call_user_func($closure, $item);
            $mapped->set($key, $newItem);
        }

        return $mapped;
    }

    /**
     * Apply callback to each element of internally stored array
     * 
     * @param \Closure $closure
     * @return self
     */
    public function walk(\Closure $closure): self
    {
        $itemsWalked = [];

        foreach($this->items() as $key => $item) {
            $newItem = call_user_func($closure, $item);

            $itemsWalked[$key] = $newItem; 
        }

        $this->items = $itemsWalked;

        return $this;
    }

    /**
     * Returns the first item that satisfies the provided testing function.
     * If no values satisfy the testing function, null is returned.
     *
     * @param \Closure $callback
     * @return mixed|null
     */
    public function find(\Closure $callback)
    {
        foreach($this->items as $item) {
            if (boolval(call_user_func($callback, $item))) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns index of the first item that satisfies the provided testing function.
     * If no values satisfy the testing function, null is returned. 
     * @return mixed|null
     */
    public function findIndex(\Closure $callback): ?int
    {
        foreach($this->items as $key => $item) {
            if (boolval(call_user_func($callback, $item))) {
                return $key;
            }
        }

        return null;
    }

    /**
     * This method will attempt to add up all elements of the array
     * 
     * @return float|int
     */
    public function sumValues(): float|int
    {
        return $this->reduce(
            function($carry, $item) {
                if(is_null($carry)) {
                    return $item;
                }
                return $carry + $item;
            },
            null
        );
    }

    /**
     * This method will check if array contains objects, 
     * if yes it will attempt to add up given properties of the objects
     * 
     * @return int|float
     */
    public function sumAttribute(string $attribute): float|int
    {
        $sum = 0;
        foreach($this->values() as $item) {
            $sum += $item->$attribute;
        }

        return $sum;
    }

    /**
     * Reduce array using callback function
     * eg. callback: function($carry, $item) { return $carry + $item; )
     *
     * @param \Closure $callback
     * @param $initial
     * @return mixed|null
     */
    public function reduce(\Closure $callback, $initial = null)
    {
        return array_reduce($this->values(), $callback, $initial);
    }

    /**
     * Reverse the orider of stored items collection
     * 
     * @return self
     */
    public function reverse(): self
    {
        $this->items = array_reverse($this->items);

        return $this;
    }

    /**
     * Returns new instance with reversed order of items
     * @return self
     */
    public function reversed(): self
    {
        $newInstance = $this->replicateSelfWith($this->items);

        return $newInstance->reverse();
    }

    /**
     * If the number of rotations is positive is equivalent to successively calling $array->push($array->shift()) 
     * or if the number is negative $array->unshift($array->pop()) . 
     * 
     * @param int @rotations
     * @return self
     */
    public function rotate(int $rotations): self
    {
        if ($rotations > 0) {
            $this->push($this->shift());
            $rotations -= 1;
            $this->rotate($rotations);
        }

        if ($rotations < 0) {
            $this->unshift($this->pop());
            $rotations += 1;
            $this->rotate($rotations);
        }

        return $this;
    }

    /**
     * Returns the array of common elements of two arrays
     * @return self
     */
    public function intersect(self $other): self
    {
        return $this->replicateSelfWith(
            array_intersect($this->items, $other->items)
        );
    }

    /**
     * Alias for clone
     * 
     * @return self
     */
    public function copy(): self
    {
        return $this->clone();
    }

    /**
     * Removes item in the first position in array and returns it,
     * thus shortening the array by one element and moving everything down
     * 
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Unsets the first element of the array, and moves everything down
     * 
     * @return self
     */
    public function removeFirst(): self
    {
        $this->shift();

        return $this;
    }

    /**
     * Wraper for array_unshift, adds element at the begining of the array and moves everything down
     * 
     * @return self
     */
    public function prepend(mixed $item): self
    {
        $this->unshift($item);

        return $this;
    }

    public function unshift(mixed $item): self
    {
        array_unshift($this->items, $item);

        return $this;
    }

    /**
     * Each element of the array will be given as argument to provided callback,
     * and stored under key that callback provides
     *
     * callback that provides the key, must return something that can be used as a key
     *
     * @param \Closure $keyProvider
     * @return EasyArray
     */
    public function keyBy(\Closure $keyProvider): self
    {
        $newArray = [];

        foreach($this->items as $key => $item) {
            $newKey = call_user_func($keyProvider, $item);
            $newArray[$newKey] = $item;
        }

        $this->items = $newArray;

        return $this;
    }

    public function keySort(?\Closure $sortFunc = null): self
    {
        if(is_null($sortFunc)) {
            ksort($this->items);
        } else {
            uksort($this->items, $sortFunc);
        }

        return $this;
    }

    public function keySorted(?\Closure $sortFunc = null): self
    {
        $newInstance = $this->replicateSelfWith(
            $this->items
        );

        return $newInstance->keySort($sortFunc);
    }

    /**
     * Sorts the stored array using the provided function, if not function is provided
     * if no sorting function is provided php build-in sort() is called (asc order)
     * 
     * Sorted array does not retain previous keys.
     * 
     * The comparison function must return an integer less than, equal to, or greater than zero 
     * if the first argument is considered to be respectively less than, equal to, or greater than the second.
     * 
     * @param ?\Closure $sortFunc = null
     * @return self 
     */
    public function sort(?\Closure $sortFunc = null): self
    {
        if(is_null($sortFunc)) {
            sort($this->items);
        } else {
            usort($this->items, $sortFunc);
        }

        return $this;
    }

    /**
     * Creates new instance and sorts the array with provided callback
     * if no sorting function is provided php build-in sort() is called
     *
     * Sorted array does not retain previous keys
     *
     * @param ?\Closure $sortFunc
     * @return EasyArray
     */
    public function sorted(?\Closure $sortFunc = null): self
    {
        $newInstance = $this->replicateSelfWith(
            $this->items
        );

        return $newInstance->sort($sortFunc);
    }

    /**
     * Returns a slice of an array, wrapped in EasyArray
     * if the offset is larger than the size of the array, an empty array is returned
     *
     * Wrapper for php array_slice built-in function
     *
     * @param int $offset
     * @param ?int $length
     * @param bool $preserveKeys
     * @return EasyArray
     */
    public function slice(
        int $offset,
        ?int $length = null,
        bool $preserveKeys = false
    ): self {
        return $this->replicateSelfWith(
            array_slice(
                $this->items, 
                $offset, 
                $length, 
                $preserveKeys
            )
        );
    }

    /**
     * Removes all elements of array
     * 
     * @return EasyArray
     */
    public function purge(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * Check if value is present in the array
     * wrapper for php built-in in_array()
     *
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public function contains(mixed $value): bool
    {
        if ($this->isEmpty()) {
            return false;
        }
    
        return in_array($value, $this->items);
    }

    /**
     * Checks if each element of other array is present in this array
     * value order is ignored
     *
     * @param EasyArray $other
     * @return bool
     */
    public function includes(self $other): bool
    {
        if ($other->count() > $this->count()) {
            return false;
        }

        return $other->diff($this)->count() == 0;
    }

    /**
     * Checks if each element of other array is present in this array
     * and both arrays have elements in common part in the same order
     *
     * @param EasyArray $other
     * @return bool
     */
    public function includesInOrder(self $other): bool
    {
        if ($other->count() > $this->count()) {
            return false;
        }

        foreach($this->items() as $item) {
            if($item === $other->first()) {
                for($i = 0; $i < $other->count();  $i++) {
                    if ($this->values()[$i] != $other->values()[$i]) {
                        return false;
                    }
                }

                return true;

            } else {
                return $this
                    ->copy()
                    ->removeFirst()
                    ->includesInOrder($other)
                ;
            }
        }

        return false;
    }

    /**
     * Checks if both arrays have the same length
     * and store elements which are equal in the same order
     *
     * @param EasyArray $other
     * @return bool
     */
    public function isSameAs(self $other): bool
    {
        return $this->count() == $other->count()
            && $this->includesInOrder($other)
        ;
    }

    /**
     * Fetch last element of array
     * returns null if $items is empty
     * 
     * @return mixed|null
     */
    public function last(): mixed
    {
        if(count($this->items) == 0) {
            return null;
        }

        $index = count($this->items);

        return array_values($this->items)[$index - 1];
    }

    /**
     * Will merge two instances of EasyArray, returns new instance of EasyArray
     * 
     * @param self $other
     * @return self new instance of EasyArray
     */
    public function merge(self $other): self
    {
        return $this->replicateSelfWith(
            array_merge($this->items, $other->items())
        );
    }

    /**
     * Wrapper for php build in array_values()
     * 
     * @return array
     */
    public function values(): array
    {
        return array_values($this->items);
    }

    /**
     * Wrapper for build in array_keys()
     * 
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Returns internally stored array
     *
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Wrapper for php build in array_key_exists_function
     *
     * @param mixed $key
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Wrapper for php build in array_key_exists_function
     *
     * @param mixed $key
     */
    public function arrayKeyExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Retrieves a value stored under given index,
     * if not found null is returned
     *
     * @param int|string $index
     * @return mixed|null
     */
    public function get(int|string $index): mixed
    {
        if (isset($this->items[$index])) {
            return $this->items[$index];
        }
        return null;
    }

    /**
     * Return index of searched element
     *
     * @param mixed $searched
     * @return int|string|null
     */
    public function getIndex(mixed $searched): int|string|null
    {
        foreach($this->items as $index => $item) {
            if($searched === $item) {
                return $index;
            }
        }

        return null;
    }

    public function current(): mixed
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): mixed
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function isNotEmpty(): bool
    {
        return $this->count() >= 1;
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    private function replicateSelfWith(array $items = []): self
    {
        if($this->cloneItemsOnReplicate) {
            return new self($this->cloner->clone($items));
        }

        return new self($items);
    }
}
