<?php

namespace App\Repository;

use App\Exception\ModelNotFoundException;
use App\Model\TeamModel;

class TeamRepository
{
    private $teams;

    public function __construct()
    {
        $this->teams = [
            5  => new TeamModel(5,  'Chelsea'),
            10 => new TeamModel(10, 'Arsenal'),
            15 => new TeamModel(15, 'Manchester City'),
            20 => new TeamModel(20, 'Liverpool'),
        ];
    }

    /**
     * @return TeamModel[]
     */
    public function findAll(): array
    {
        return array_values($this->teams);
    }

    public function findById(int $id): TeamModel
    {
        if (!isset($this->teams[$id])) {
            throw new ModelNotFoundException("Model with id:$id not found");
        }

        return $this->teams[$id];
    }
}