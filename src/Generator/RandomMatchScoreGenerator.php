<?php

namespace App\Generator;

class RandomMatchScoreGenerator implements MatchScoreGeneratorInterface
{
    public function generate(): array
    {
        $pointsSum   = random_int(0, 8);
        $firstScore   = random_int(0, $pointsSum);
        $secondScore = $pointsSum - $firstScore;
        return [$firstScore, $secondScore];
    }
}