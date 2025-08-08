<?php

namespace App\Domain\Football\League;

use Illuminate\Contracts\Support\Arrayable;

class LeagueEntities implements Arrayable
{
    public function __construct(
        private string $id,
        private string $name,
        private string $country,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
        ];
    }
}
