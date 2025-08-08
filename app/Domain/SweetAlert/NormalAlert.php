<?php

namespace App\Domain\SweetAlert;

use Illuminate\Contracts\Support\Arrayable;

class NormalAlert implements Arrayable
{
    public function __construct(private string $title, private string $type) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
        ];
    }
}
