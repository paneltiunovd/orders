<?php

namespace app\models;

use app\Enums\OrderModeEnum;
use app\Enums\OrderStatusEnum;
use app\models\DTO\DateDTO;
use app\models\DTO\ServiceFrontDTO;
use Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $created_at
 * @property integer $status
 * @property integer $mode
 * @property integer|null $id
 * @property Users|null $users
 * @property integer|null $quantity
 * @property string|null $link
 * @property Services $service
 */
class Orders extends ActiveRecord
{
    /**
     * @var mixed|null
     */
    public $status_count;

    public function getStatusToString(): string {
        return OrderStatusEnum::texts[$this->status];
    }

    public function getModeToString(): string {
        return OrderModeEnum::texts[$this->mode];
    }

    public function getDateObject(): DateDTO {
        return new DateDTO($this->created_at);
    }


    public function getUsers(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    public function getService(): ActiveQuery
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }



}