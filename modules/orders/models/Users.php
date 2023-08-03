<?php

namespace app\modules\orders\models;

use yii\db\ActiveRecord;

/**
 * @property mixed $last_name
 * @property mixed $first_name
 */
class Users extends ActiveRecord
{


    public function getName(
//        bool $param,
//        string $search
    ): string // TODO, если останется время, подсветить что именно он распознал в имени как искомый
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}