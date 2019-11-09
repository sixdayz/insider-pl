<?php

namespace App\Generator;

class RandomMatchScoreGenerator implements MatchScoreGeneratorInterface
{
    /** @var int */
    private $maxGoalsPerMatch;

    public function __construct(int $maxGoalsPerMatch)
    {
        $this->maxGoalsPerMatch = $maxGoalsPerMatch;
    }

    public function generate(): array
    {
        $pointsSum   = random_int(0, $this->maxGoalsPerMatch);
        $firstScore   = random_int(0, $pointsSum);
        $secondScore = $pointsSum - $firstScore;
        return [$firstScore, $secondScore];
    }
}