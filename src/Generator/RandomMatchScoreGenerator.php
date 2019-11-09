<?php

namespace App\Generator;

class RandomMatchScoreGenerator implements MatchScoreGeneratorInterface
{
    public function generate(): array
    {
        $pointsSum   = random_int(0, 6);
        $firstScore   = random_int(0, $pointsSum);
        $secondScore = $pointsSum - $firstScore;
        return [$firstScore, $secondScore];
    }
}