<?php
declare(strict_types=1);

namespace EasyArray\TypeFactory;

use EasyArray\TypeFactory\Type;

class TypeFactory
{
    public function make($value): Type
    {
        return (new Type($this->getTypeNameFrom($value)));
    }

    /**
     * @param mixed $value
     */
    private function getTypeNameFrom($value): string
    {
        $type = gettype($value);
        if ($type == "object") {
            return get_class($value);
        }

        return $type;
    }
}