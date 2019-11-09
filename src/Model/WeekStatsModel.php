<?php

namespace App\Model;

class WeekStatsModel
{
    /** @var TeamStatsModel[] */
    private $teams;

    /** @var MatchResultModel[] */
    private $matches;

    public function __construct()
    {
        $this->teams = [];
        $this->matches = [];
    }

    public function addTeam(TeamStatsModel $team): self
    {
        $this->teams[$team->getId()] = $team;
        return $this;
    }

    public function addMatch(MatchResultModel $match): self
    {
        $this->matches[] = $match;
        return $this;
    }

    /**
     * @return TeamStatsModel[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return MatchResultModel[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }
}