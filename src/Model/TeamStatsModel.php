<?php

namespace App\Model;

class TeamStatsModel implements \JsonSerializable
{
    /** @var TeamModel */
    private $team;

    /** @var int */
    private $points;

    /** @var int */
    private $played;

    /** @var int */
    private $won;

    /** @var int */
    private $drawn;

    /** @var int */
    private $lost;

    /** @var int */
    private $goalDifference;

    public function __construct(
        TeamModel $team,
        int $played = 0,
        int $won = 0,
        int $drawn = 0,
        int $lost = 0,
        int $gd = 0,
        int $points = 0
    )
    {
        $this->team             = $team;
        $this->points           = $points;
        $this->played           = $played;
        $this->won              = $won;
        $this->drawn            = $drawn;
        $this->lost             = $lost;
        $this->goalDifference   = $gd;
    }

    public function getId(): int
    {
        return $this->team->getId();
    }

    public function getTeam(): TeamModel
    {
        return $this->team;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function getWon(): int
    {
        return $this->won;
    }

    public function getDrawn(): int
    {
        return $this->drawn;
    }

    public function getLost(): int
    {
        return $this->lost;
    }

    public function getGoalDifference(): int
    {
        return $this->goalDifference;
    }

    public function applyMatchResult(MatchResultModel $result): void
    {
        if ($result->isParticipated($this->team)) {
            $this->played++;

            if ($result->isWon($this->team)) {
                $this->won++;
                $this->points += 3;
            }

            if ($result->isDrawn($this->team)) {
                $this->drawn++;
                $this->points++;
            }

            if ($result->isLost($this->team)) {
                $this->lost++;
            }
        }
    }

    public function jsonSerialize()
    {
        return [
            'team'      => $this->team->getName(),
            'points'    => $this->points,
            'played'    => $this->played,
            'won'       => $this->won,
            'drawn'     => $this->drawn,
            'lost'      => $this->lost,
            'gd'        => $this->goalDifference,
        ];
    }
}