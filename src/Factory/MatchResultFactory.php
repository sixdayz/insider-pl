<?php

namespace App\Factory;

use App\Exception\ModelNotFoundException;
use App\Model\MatchResultModel;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MatchResultFactory
{
    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(TeamRepository $tr)
    {
        $this->teamRepository = $tr;
    }

    /**
     * @param Request $request
     * @return MatchResultModel[]
     * @throws ModelNotFoundException
     */
    public function getMatchesFromRequest(Request $request) : array
    {
        $results = [];
        $data    = $request->request->get('matches');

        foreach ($data as $weekMatches) {
            foreach ($weekMatches as $match) {

                $resultMeta = [];
                foreach ($match as $teamId => $score) {
                    $resultMeta[] = [$teamId, $score];
                }

                $results[] = new MatchResultModel(
                    $this->teamRepository->findById($resultMeta[0][0]),
                    $resultMeta[0][1],
                    $this->teamRepository->findById($resultMeta[1][0]),
                    $resultMeta[1][1],
                );
            }
        }

        return $results;
    }
}