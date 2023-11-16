<?php

namespace EasyArray;

class EasyArray implements \ArrayAccess, \Iterator
{
    /** @var int */
    public $position;

    /** @var array */
    public $items = [];

    /** @var bool */
    public $strict = false;

    private $type;

    public function __construct(
        array $items,
        bool $strict = false
    ){
        $this->items = array_values($items);
        $this->strict = $strict;
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    public function push($item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function set($index, $value): self
    {
        $this->items[$index] = $value;
        
        return $this;
    }

    public function map(\Closure $closure): self
    {
        return new self(
            array_map(
                $closure,
                $this->items
            )
        );
    }

    public function walk(\Closure $closure): self
    {
        foreach($this->items as $key => $item) {
            $this->items[$key] = call_user_func($closure, $item);
        }

        return $this;
    }

    /**
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
      * @param \Closure $callback
      * @return self
      */
    public function filter(\Closure $callback): self
    {
        $filteredItems = [];
        foreach($this->items as $item) {
            if(boolval(call_user_func($callback, $item))) {
                $filteredItems[] = $item;
            }
        }

        return new self($filteredItems);
    }

    public function flatten(int $levels = 5): self
    {
        $flat = [];

        foreach($this->items as $value) {
            if(is_array($value) && $levels >= 1) {

                if ($levels >= 1) {
                    $easy = new self($value);
                    $flat = array_merge($flat, $easy->flatten($levels - 1)->values());

                } else {
                    $flat[] = $value;
                }

            } else {
                $flat[] = $value;
            }
        }

        return new self($flat);
    }

    public function reverse()
    {

    }

    public function shift()
    {

    }

    public function unshift()
    {

    }

    public function sort(): self
    {

    }

    /**
     * Returns null if $items is empty
     * @return mixed|null
     */
    public function first()
    {
        if(count($this->items) == 0) {
            return null;
        }

        return array_values($this->items)[0];
    }

    /**
     * Returns null if $items is empty
     * @return mixed|null
     */
    public function last()
    {
        if(count($this->items) == 0) {
            return null;
        }

        return array_values($this->items)[count($this->items) - 1];
    }

    public function merge(self $other): self
    {
        return new self(array_merge($this->items, $other->items()));
    }

    public function values(): array
    {
        return array_values($this->items);
    }

    public function items(): array
    {
        return $this->items;
    }

    /**
     * @param int $index
     * @return mixed|null
     */
    public function get(int $index)
    {
        if (isset($this->items[$index])) {
            return $this->items[$index];
        }
        return null;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}