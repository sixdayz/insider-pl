<?php

namespace App\Factory;

use App\Exception\ModelNotFoundException;
use App\Helper\JsonHelper;
use App\Model\MatchResultModel;
use App\Repository\TeamRepository;

class MatchResultFactory
{
    /** @var TeamRepository */
    private $teamRepository;

    public function __construct(TeamRepository $tr)
    {
        $this->teamRepository = $tr;
    }

    /**
     * JSON Example:
     *
     * [{"5":0,"15":3},{"20":0,"5":2}]
     * [{"first_team_id":score,"second_team_id":score},{…}]
     *
     * @param string $json JSON string after match result model json encoded
     * @return MatchResultModel[]
     * @throws ModelNotFoundException
     */
    public function matchResultsFromJson(string $json): array
    {
        $results = [];
        $data    = JsonHelper::decode($json);

        foreach ($data as $match) {

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

        return $results;
    }
}