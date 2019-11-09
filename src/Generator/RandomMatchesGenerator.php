<?php

namespace App\Generator;

use App\Model\MatchResultModel;
use App\Model\TeamModel;

class RandomMatchesGenerator implements MatchesGeneratorInterface
{
    public function generate(array $teams): array
    {
        $resultPairs  = [];

        /** @var TeamModel $firstTeam */
        foreach ($teams as $firstTeam) {
            for ($i = 1; $i <= 2; $i++) {

                /** @var TeamModel $secondTeam */
                foreach ($teams as $secondTeam) {

                    $ids = [$firstTeam->getId(), $secondTeam->getId()];
                    sort($ids);

                    $uniqueKey = sprintf(
                        '%d-%d-%d',
                        $ids[0],
                        $ids[1],
                        $i
                    );

                    $matchExists     = isset($resultPairs[$uniqueKey]);
                    $matchWithMyself = $secondTeam->getId() === $firstTeam->getId();

                    if (!$matchExists && !$matchWithMyself) {
                        $resultPairs[$uniqueKey] = [$firstTeam->getName(), $secondTeam->getName()];
                    }
                }
            }
        }

        $values = array_values($resultPairs);
        shuffle($values);

        return $values;
    }
}