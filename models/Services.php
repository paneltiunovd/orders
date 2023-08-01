<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property mixed|null $name
 */
class Services extends ActiveRecord
{
    public function orders() {
        return $this->hasMany(Orders::class, ['service_id' => 'id']);
    }
}