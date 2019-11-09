<?php

namespace App\Factory;

use App\Generator\MatchesResultsGenerator;
use App\Model\MatchResultModel;
use App\Model\TeamModel;
use App\Model\TeamStatsModel;
use App\Model\WeekStatsModel;

class WeeksStatsFactory
{
    /** @var int */
    private $matchesPerWeek;

    public function __construct(int $matchesPerWeek)
    {
        $this->matchesPerWeek = $matchesPerWeek;
    }

    /**
     * @param TeamModel[] $teams
     * @param MatchResultModel[] $matches
     * @return WeekStatsModel[]
     */
    public function getWeeks(array $teams, array $matches): array
    {
        $weeksCount = ceil(count($matches) / $this->matchesPerWeek);
        $weeks      = [];

        // Initial teams stats
        // before any matches

        $teamsStats = array_map(static function (TeamModel $tm) {
            return new TeamStatsModel($tm);
        }, $teams);

        for ($i = 0; $i < $weeksCount; $i++) {

            $week = new WeekStatsModel($i + 1, $teamsStats);

            // filling week with matches info

            for ($j = 0; $j < $this->matchesPerWeek; $j++) {
                if ($match = array_shift($matches)) {
                    $week->addMatch($match);
                }
            }

            // clone team stats for next week

            $teamsStats = array_map(static function (TeamStatsModel $ts) {
                return new TeamStatsModel(
                    $ts->getTeam(),
                    $ts->getPlayed(),
                    $ts->getWon(),
                    $ts->getDrawn(),
                    $ts->getLost(),
                    $ts->getGoalDifference(),
                    $ts->getPoints()
                );
            }, $week->getTeamsStats());

            $weeks[] = $week;
        }

        return $weeks;
    }
}