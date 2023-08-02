<?php

namespace app\models\Enums;

use app\models\Enums\Traits\Enumerable;

class OrderStatusEnum
{
    use Enumerable;

    const NF = -1;
    const PE = 0;
    const IP = 1;
    const CP = 2;
    const CN = 3;
    const FL = 4;
    const texts = [
        self::NF => 'not found',
        self::PE => 'Pending',
        self::IP => 'In progress',
        self::CP => 'Completed',
        self::CN => 'Canceled',
        self::FL => 'Failed',
    ];
}