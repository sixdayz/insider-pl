<?php

namespace App\Controller;

use App\Exception\ModelNotFoundException;
use App\Factory\MatchResultFactory;
use App\Factory\WeeksStatsFactory;
use App\Generator\MatchesResultsGenerator;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error;

class StartController
{
    /** @var MatchesResultsGenerator */
    private $matchesGenerator;

    /** @var TeamRepository */
    private $teamRepository;

    /** @var WeeksStatsFactory */
    private $weeksFactory;

    /** @var MatchResultFactory */
    private $matchesFactory;

    /** @var Environment */
    private $twig;

    public function __construct(
        MatchesResultsGenerator $generator,
        TeamRepository $repository,
        WeeksStatsFactory $weeksFactory,
        MatchResultFactory $matchFactory,
        Environment $twig
    )
    {
        $this->matchesGenerator = $generator;
        $this->teamRepository = $repository;
        $this->weeksFactory = $weeksFactory;
        $this->matchesFactory = $matchFactory;
        $this->twig = $twig;
    }

    /**
     * @Route(path="/", methods={"GET"})
     * @return Response
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     */
    public function newAction(): Response
    {
        $teams   = $this->teamRepository->findAll();
        $matches = $this->matchesGenerator->generate($teams);
        $weeks   = $this->weeksFactory->getWeeks($teams, $matches);

        return Response::create($this->twig->render('start/by-weeks.html.twig', [
            'current_week' => $weeks[0],
            'has_next'     => true,
            'next_week'    => $weeks[1],
            'weeks'        => $weeks
        ]));
    }

    /**
     * @Route(
     *     path = "/weeks/{week}",
     *     methods = {"GET", "POST"},
     *     requirements = {"week" = "^\d+$"}
     * )
     *
     * @param Request $request
     * @return Response
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     * @throws ModelNotFoundException
     */
    public function byWeeksAction(Request $request): Response
    {
        if (!$request->request->has('matches')) {
            return RedirectResponse::create('/');
        }

        $number  = $request->get('week') - 1;
        $teams   = $this->teamRepository->findAll();
        $matches = $this->matchesFactory->getMatchesFromRequest($request);
        $weeks   = $this->weeksFactory->getWeeks($teams, $matches);

        return Response::create($this->twig->render('start/by-weeks.html.twig', [
            'current_week' => $weeks[$number],
            'has_next'     => isset($weeks[$number + 1]),
            'next_week'    => $weeks[$number + 1] ?? null,
            'weeks'        => $weeks
        ]));
    }

    /**
     * @Route(path="/weeks/all", methods={"GET", "POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     * @throws ModelNotFoundException
     */
    public function allWeeksAction(Request $request): Response
    {
        if (!$request->request->has('matches')) {
            return RedirectResponse::create('/');
        }

        $teams   = $this->teamRepository->findAll();
        $matches = $this->matchesFactory->getMatchesFromRequest($request);
        $weeks   = $this->weeksFactory->getWeeks($teams, $matches);

        return Response::create($this->twig->render('start/all-weeks.html.twig', [
            'weeks' => $weeks
        ]));
    }
}