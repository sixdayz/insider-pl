<?php

namespace App\Controller;

use App\Factory\WeeksStatsFactory;
use App\Generator\MatchesResultsGenerator;
use App\Generator\MatchScoreGeneratorInterface;
use App\Model\TeamModel;
use App\Model\TeamStatsModel;
use App\Model\WeekStatsModel;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StartController
{
    /** @var MatchesResultsGenerator */
    private $matchesGenerator;

    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(MatchesResultsGenerator $generator, TeamRepository $repository)
    {
        $this->matchesGenerator = $generator;
        $this->teamRepository = $repository;
    }

    /**
     * @Route(path="/", methods={"GET", "POST"})
     * @return Response
     */
    public function byWeeksAction(): Response
    {
        $enc = $this->matchesGenerator->generate($this->teamRepository->findAll());
        return JsonResponse::create($enc);
    }

    /**
     * @Route(path="/all", methods={"GET", "POST"})
     * @param WeeksStatsFactory $weeksFactory
     * @return JsonResponse
     */
    public function allWeeksAction(WeeksStatsFactory $weeksFactory): Response
    {
        $teams   = $this->teamRepository->findAll();
        $matches = $this->matchesGenerator->generate($teams);

        return JsonResponse::create($weeksFactory->getWeeks($teams, $matches));
    }
}