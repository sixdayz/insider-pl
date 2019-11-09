<?php


namespace App\Model;


use App\Enum\GoalTypeEnum;

class MatchResultModel implements \JsonSerializable
{
    /** @var array */
    private $results;

    public function __construct(
        TeamModel $firstTeam,
        int $firstTeamGoalCount,
        TeamModel $secondTeam,
        int $secondTeamGoalCount
    )
    {
        $this->results = [
            $firstTeam->getId()   => [
                'team' => $firstTeam,
                'score' => $firstTeamGoalCount
            ],
            $secondTeam->getId() => [
                'team' => $secondTeam,
                'score' => $secondTeamGoalCount
            ]
        ];
    }

    public function isWon(TeamModel $team): bool
    {
        return $this->getScoredCoalsCount($team) > $this->getConcededGoalsCount($team);
    }

    public function isLost(TeamModel $team): bool
    {
        return $this->getScoredCoalsCount($team) < $this->getConcededGoalsCount($team);
    }

    public function isDrawn(TeamModel $team): bool
    {
        return $this->getScoredCoalsCount($team) === $this->getConcededGoalsCount($team);
    }

    public function getScoredCoalsCount(TeamModel $team): int
    {
        return $this->isParticipated($team)
            ? $this->getGoalTypes($team)[GoalTypeEnum::SCORED]
            : 0;
    }

    public function getConcededGoalsCount(TeamModel $team): int
    {
        return $this->isParticipated($team)
            ? $this->getGoalTypes($team)[GoalTypeEnum::CONCEDED]
            : 0;
    }

    public function isParticipated(TeamModel $team): bool
    {
        return isset($this->results[$team->getId()]);
    }

    private function getGoalTypes(TeamModel $team): array
    {
        $result = [];

        if ($this->isParticipated($team)) {
            foreach ($this->results as $teamId => $data) {

                $type = $teamId === $team->getId()
                    ? GoalTypeEnum::SCORED
                    : GoalTypeEnum::CONCEDED;

                $result[$type] = $data['score'];
            }
        }

        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->results;
    }
}