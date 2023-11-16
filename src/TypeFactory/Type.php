<?php

namespace EasyArray\TypeFactory;

class Type
{
    /** @var string */
    private $typeName;

    public function __construct(
        string $typeName
    ) {
        $this->typeName = $typeName;
    }

    public function hasTypeSet(): bool
    {
        return !is_null($this->typeName);
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function equals(self $other): bool
    {
        return $this->getTypeName() === $other->getTypeName();
    }
}