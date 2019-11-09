<?php

namespace App\Generator;

use App\Model\MatchResultModel;
use App\Model\TeamModel;

class MatchesResultsGenerator
{
    /** @var MatchScoreGeneratorInterface */
    private $scoreGenerator;

    /** @var MatchesGeneratorInterface */
    private $matchesGenerator;

    public function __construct(MatchesGeneratorInterface $mg, MatchScoreGeneratorInterface $msg)
    {
        $this->scoreGenerator = $msg;
        $this->matchesGenerator = $mg;
    }

    /**
     * @param TeamModel[] $teams
     * @return MatchResultModel[]
     */
    public function generate(array $teams): array
    {
        $result = [];
        foreach ($this->matchesGenerator->generate($teams) as $matchTeams) {

            $score = $this->scoreGenerator->generate();

            $result[] = new MatchResultModel(
                $matchTeams[0],
                $score[0],
                $matchTeams[1],
                $score[1]
            );
        }

        return $result;
    }
}