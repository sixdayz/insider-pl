<?php

namespace App\Controller;

use App\Generator\MatchesResultsGenerator;
use App\Model\TeamModel;
use App\Model\TeamStatsModel;
use App\Model\WeekStatsModel;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StartController
{
    /**
     * @Route(path="/", methods={"GET","POST"})
     * @param MatchesResultsGenerator $generator
     * @param TeamRepository $repository
     * @return Response
     */
    public function byWeeksAction(MatchesResultsGenerator $generator, TeamRepository $repository): Response
    {
        $enc = $generator->generate($repository->findAll());
        return JsonResponse::create($enc);
    }

    /**
     * @Route(path="/all", methods={"GET","POST"})
     * @param int $matchesPerWeek
     * @param MatchesResultsGenerator $generator
     * @param TeamRepository $repository
     * @return JsonResponse
     */
    public function allWeeksAction(int $matchesPerWeek, MatchesResultsGenerator $generator, TeamRepository $repository): Response
    {
        $teams      = $repository->findAll();
        $matches    = $generator->generate($teams);
        $weeksCount = ceil(count($matches) / $matchesPerWeek);
        $weeks      = [];

        // Initial teams stats
        // before any matches

        $teamsStats = array_map(static function (TeamModel $tm) {
            return new TeamStatsModel($tm);
        }, $teams);

        for ($i = 0; $i < $weeksCount; $i++) {

            $week = new WeekStatsModel($i + 1, $teamsStats);

            // filling week with matches info

            for ($j = 0; $j < $matchesPerWeek; $j++) {
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

        return JsonResponse::create($weeks);
    }
}