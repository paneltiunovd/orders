<?php

namespace app\Enums;

class TypeEnum
{

    public const EQ_OPERATOR = '=';
    public const LIKE_OPERATOR = 'like';
    public const LINK_TYPE = 2;
    public const AVAILABLE_TYPES_FIELDS_AND_OPERATOR = [
        self::ID_TYPE => ['id', self::EQ_OPERATOR],
        self::MODE_TYPE => ['mode', self::EQ_OPERATOR],
        self::LINK_TYPE => ['link', self::LIKE_OPERATOR],
        self::USERNAME_TYPE => [null, self::LIKE_OPERATOR],
    ];
    public const USERNAME_TYPE = 3;
    public const ID_TYPE = 1;
    public const MODE_TYPE = 4;
}