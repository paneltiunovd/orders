<?php

namespace app\Enums;

use app\Enums\Traits\Enumerable;

class SearchTypeEnum
{
    use Enumerable;

    public const ID_TYPE = 1;
    public const LINK_TYPE = 2;
    public const USERNAME_TYPE = 3;
    public const MODE_TYPE = 4;
    public const STATUS_TYPE = 5;
    public const AVAILABLE_TYPES_FIELDS_AND_OPERATOR = [
        self::ID_TYPE => ['id', OperatorEnum::EQ_OPERATOR],
        self::MODE_TYPE => ['mode', OperatorEnum::EQ_OPERATOR],
        self::LINK_TYPE => ['link', OperatorEnum::LIKE_OPERATOR],
        self::USERNAME_TYPE => [null, OperatorEnum::LIKE_OPERATOR],
        self::STATUS_TYPE => ['status', OperatorEnum::EQ_OPERATOR],
    ];

    const available_texts_for_dropdown = [
        self::ID_TYPE => 'Order ID',
        self::LINK_TYPE => 'Link',
        self::USERNAME_TYPE => 'Username',
    ];
}