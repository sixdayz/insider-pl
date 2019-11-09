<?php

namespace App\Generator;

use App\Model\MatchResultModel;
use App\Model\TeamModel;

interface MatchesGeneratorInterface
{
    /**
     * @param TeamModel[] $teams
     * @return MatchResultModel[]
     */
    public function generate(array $teams): array;
}