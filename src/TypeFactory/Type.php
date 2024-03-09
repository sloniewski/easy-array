<?php

namespace EasyArray\TypeFactory;

class Type
{
    /** @var string */
    private $typeName;

    /** @var bool */
    private $isObject;

    public function __construct(
        string $typeName,
        bool $isObject = false
    ) {
        $this->typeName = $typeName;
        $this->isObject = $isObject;
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

    public function isObject(): bool
    {
        return $this->isObject;
    }

    public function isNull(): bool
    {
        return gettype(null) === $this->getTypeName();
    }
}