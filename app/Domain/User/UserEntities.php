<?php

namespace App\Domain\User;

use App\Infrastructure\User\Enums\UserStatus;
use DateTime;
use Illuminate\Contracts\Support\Arrayable;

class UserEntities implements Arrayable
{
    /**
     * @return void
     */
    public function __construct(
        private string $id,
        private string $nickName,
        private string $fullName,
        private string $phone,
        private UserStatus $status,
        private string $coins_silver,
        private string $coins_gold,
        private ?string $email = null,
        private ?DateTime $createdAt = null,
        private ?DateTime $updatedAt = null,
    ) {
        //
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNickName(): string
    {
        return $this->nickName;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return UserStatus
     */
    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCoinsSilver(): string
    {
        return $this->coins_silver;
    }

    /**
     * @return string
     */
    public function getCoinsGold(): string
    {
        return $this->coins_gold;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /** @return array<string, mixed>  */
    public function toArray(): array
    {
        return [
            'nickName' => $this->nickName,
            'fullName' => $this->fullName,
            'phone' => $this->phone,
            'status' => $this->status,
            'coins_silver' => $this->coins_silver,
            'coins_gold' => $this->coins_gold,
            'email' => $this->email,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
