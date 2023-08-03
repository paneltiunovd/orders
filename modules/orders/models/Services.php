<?php

namespace app\modules\orders\models;

use yii\db\ActiveRecord;

/**
 * @property mixed|null $name
 */
class Services extends ActiveRecord
{
    public function getOrders() {
        return $this->hasMany(Orders::class, ['service_id' => 'id']);
    }
}