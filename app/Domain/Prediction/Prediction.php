<?php

namespace App\Domain\Prediction;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Prediction implements Arrayable
{
    private ?int $id = null;
    private ?string $result = null;
    private ?CurrencyType $gainType = null;
    private ?string $gainAmount = null;
    private ?string $updatedBy = null;
    private ?Carbon $createdAt = null;
    private ?Carbon $updatedAt = null;

    private string $type = 'step'; // 🔧 เพิ่มตัวแปร type พร้อม default

    public function __construct(
        private string $userId,
        private string $matchId,
        private string $selectedTeamId,
        private CurrencyType $costType,
        private float $costAmount,
        ?string $type = null
    ) {
        if (!empty($type)) {
            $this->type = $type;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'match_id' => $this->getMatchId(),
            'selected_team_id' => $this->getSelectedTeamId(),
            'cost_type' => $this->getCostType()?->value,
            'cost_amount' => $this->getCostAmount(),
            'result' => $this->getResult(),
            'gain_type' => $this->getGainType()?->value,
            'gain_amount' => $this->getGainAmount(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'type' => $this->getType(), // 🔧 เพิ่ม type
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getResult(): ?string { return $this->result; }
    public function setResult(?string $result): self { $this->result = $result; return $this; }

    public function getGainType(): ?CurrencyType { return $this->gainType; }
    public function setGainType(?CurrencyType $gainType): self { $this->gainType = $gainType; return $this; }

    public function getGainAmount(): ?string { return $this->gainAmount; }
    public function setGainAmount(?string $gainAmount): self { $this->gainAmount = $gainAmount; return $this; }

    public function getUserId(): string { return $this->userId; }
    public function getMatchId(): string { return $this->matchId; }
    public function getSelectedTeamId(): string { return $this->selectedTeamId; }

    public function getUpdatedBy(): ?string { return $this->updatedBy; }
    public function setUpdatedBy(?string $updatedBy): self { $this->updatedBy = $updatedBy; return $this; }

    public function getCreatedAt(): ?Carbon { return $this->createdAt; }
    public function setCreatedAt(?Carbon $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): ?Carbon { return $this->updatedAt; }
    public function setUpdatedAt(?Carbon $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    public function getCostType(): CurrencyType { return $this->costType; }
    public function getCostAmount(): float { return $this->costAmount; }

    // 🔧 ใหม่: get/set type
    public function getType(): string { return $this->type; }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
