<?php

namespace App\Infrastructure\Factories\User;

use App\Domain\User\UserEntities;
use App\Domain\User\UserEntitiesCollection;
use App\Infrastructure\User\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserFactories
{
    public static function createFromModel(Collection $users): UserEntitiesCollection
    {
        $userEntitiesCollection = new UserEntitiesCollection();
        /** @var User $user */
        foreach ($users as $user) {
            $userEntitiesCollection->add(new UserEntities(
                $user->id,
                $user->nick_name,
                $user->full_name,
                $user->phone,
                UserStatus::tryFrom($user->status),
                $user->coins_silver,
                $user->coins_gold,
                $user->email,
                $user->created_at,
                $user->updated_at,
            ));
        }

        return $userEntitiesCollection;
    }
}
