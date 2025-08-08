<?php

namespace App\Infrastructure\Collection;

use Ramsey\Collection\Collection;

class TypeCollection extends Collection
{
    protected string $type;

    public function __construct()
    {
        parent::__construct($this->type);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->data as $value) {
            $result[] = $value->toArray();
        }

        return $result;
    }

    public function removeEmpty(array $data): array
    {
        return array_filter($data);
    }
}
