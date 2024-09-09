<?php
declare(strict_types=1);

namespace EasyArray\Cloner;

class Cloner
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function clone(mixed $value): mixed
    {
        if(is_array($value)) {
            return array_map(
                function($item) {
                    return $this->clone($item);
                },
                $value
            );
        }

        if(gettype($value) == "object") {
            return clone($value);
        }

        $newVal = $value;

        return $newVal;
    }
}