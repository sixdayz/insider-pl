<?php

namespace App\Model;

class WeekStatsModel implements \JsonSerializable
{
    /** @var TeamStatsModel[] */
    private $teamsStats;

    /** @var MatchResultModel[] */
    private $matches;

    /** @var int */
    private $number;

    /**
     * @param int $number
     * @param TeamStatsModel[] $teamsStats
     */
    public function __construct(int $number, array $teamsStats)
    {
        $this->number     = $number;
        $this->matches    = [];
        $this->teamsStats = $teamsStats;
    }

    public function addMatch(MatchResultModel $match): self
    {
        $this->matches[] = $match;
        foreach ($this->teamsStats as $teamStats) {
            $teamStats->applyMatchResult($match);
        }

        return $this;
    }

    /**
     * @return TeamStatsModel[]
     */
    public function getTeamsStats(): array
    {
        return $this->teamsStats;
    }

    /**
     * @return MatchResultModel[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function jsonSerialize()
    {
        return [
            'number'  => $this->number,
            'matches' => $this->matches,
            'teams'   => $this->teamsStats
        ];
    }
}