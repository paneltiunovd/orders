<?php

namespace app\Enums;

use app\Enums\Traits\Enumerable;

class OrderModeEnum
{
    use Enumerable;

    const texts = [
        self::MANUAL => 'Manual',
        self::AUTO => 'Auto',
        self::ALL => 'All',
    ];
    const ALL = -1;
    const MANUAL = 0;
    const AUTO = 1;
}