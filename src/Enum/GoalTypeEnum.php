<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static GoalTypeEnum SCORED()
 * @method static GoalTypeEnum CONCEDED()
 */
class GoalTypeEnum extends Enum
{
    public const SCORED   = 'scored';
    public const CONCEDED = 'conceded';
}
