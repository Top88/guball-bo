<?php

namespace App\Domain\Football\Match;

use App\Domain\Football\RateType;
use App\Infrastructure\Matches\Enums\MatchStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class MatchEntities implements Arrayable
{
    private ?int $leagueId = null;

    private ?float $homeTeamRate = null;

    private ?float $awayTeamRate = null;

    private ?string $result = null;

    private ?Carbon $createdAt = null;

    private ?Carbon $updatedAt = null;

    /**
     * Summary of __construct
     */
    public function __construct(
        private string $id,
        private string $homeTeamId,
        private string $awayTeamId,
        private RateType $rateType,
        private Carbon $matchDate,
        private MatchStatus $status
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getHomeTeamId(): string
    {
        return $this->homeTeamId;
    }

    public function getAwayTeamId(): string
    {
        return $this->awayTeamId;
    }

    public function getRateType(): RateType
    {
        return $this->rateType;
    }

    public function getMatchDate(): Carbon
    {
        return $this->matchDate;
    }

    public function getStatus(): MatchStatus
    {
        return $this->status;
    }

    public function getLeagueId(): ?int
    {
        return $this->leagueId;
    }

    public function setLeagueId(?int $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    public function getHomeTeamRate(): ?float
    {
        return $this->homeTeamRate;
    }

    public function setHomeTeamRate(?float $homeTeamRate): self
    {
        $this->homeTeamRate = $homeTeamRate;

        return $this;
    }

    public function getAwayTeamRate(): ?float
    {
        return $this->awayTeamRate;
    }

    public function setAwayTeamRate(?float $awayTeamRate): self
    {
        $this->awayTeamRate = $awayTeamRate;

        return $this;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?Carbon $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Summary of toArray
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'league_id' => $this->getLeagueId(),
            'home_team_id' => $this->getHomeTeamId(),
            'away_team_id' => $this->getHomeTeamId(),
            'rate_type' => $this->getRateType(),
            'home_team_rate' => $this->getHomeTeamRate(),
            'away_team_rate' => $this->getAwayTeamRate(),
            'match_date' => $this->getMatchDate(),
            'status' => $this->getStatus(),
            'result' => $this->getResult(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}
