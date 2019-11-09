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
     * @param string $json JSON string after match result model json encoded
     * @return MatchResultModel[]
     * @throws ModelNotFoundException
     */
    public function matchResultsFromJson(string $json): array
    {
        $results = [];
        $data    = JsonHelper::decode($json);

        foreach ($data as $item) {

            $resultMeta = [];
            foreach ($item as $teamId => $meta) {
                $resultMeta[] = [$teamId, $meta['score']];
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