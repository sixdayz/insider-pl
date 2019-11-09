<?php

namespace App\Controller;

use App\Generator\MatchesResultsGenerator;
use App\Helper\JsonHelper;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StartController
{
    /**
     * @Route(path="/", methods={"GET"})
     * @param MatchesResultsGenerator $generator
     * @param TeamRepository $repository
     * @return Response
     */
    public function byWeeksAction(MatchesResultsGenerator $generator, TeamRepository $repository): Response
    {
        $enc = JsonHelper::encode($generator->generate($repository->findAll()));
        return Response::create($enc);
    }

    /**
     * @Route(path="/all", methods={"GET"})
     * @return Response
     */
    public function allWeeksAction(): Response
    {
        return Response::create('All');
    }
}