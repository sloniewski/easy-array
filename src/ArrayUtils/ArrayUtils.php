<?php

declare(strict_types=1);

namespace EasyArray\ArrayUtils;

class ArrayUtils {

    public function flattenArray(array $array, int $levels): array
    {
        $flat = [];

        foreach($array as $value) {
            if(is_array($value) && $levels >= 1) {
                $flat = array_merge($flat, array_values($this->flattenArray($value, $levels - 1)));
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    public function filterArray(\Closure $callback, array $items): array
    {
        $filteredItems = [];
        foreach($items as $key => $item) {
            if (boolval(call_user_func($callback, $item))) {
                $filteredItems[$key] = $item;
            }
        }

        return $filteredItems;
    }
}