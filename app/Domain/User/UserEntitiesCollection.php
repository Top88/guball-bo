<?php

namespace App\Domain\User;

use Ramsey\Collection\Collection;

class UserEntitiesCollection extends Collection
{
    public function __construct(array $items = [])
    {
        parent::__construct(UserEntities::class, $items);
    }
}
