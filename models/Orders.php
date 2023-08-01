<?php

namespace app\models;

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
 */
class Orders extends ActiveRecord
{
    /**
     * @var mixed|null
     */
    public $status_count;

    /**
     * @throws Exception
     */
    public function getStatusToString(): string {
        if(OrderStatusEnum::byValue($this->status) === null) {
            return OrderStatusEnum::texts[OrderStatusEnum::NF];
        }
        return OrderStatusEnum::texts[$this->status];
    }

    public function getModeToString(): string {
        switch ($this->mode) {
            case "0":
                return 'Manual';
            case "1":
                return 'Auto';
            default:
                return 'not found';
        }
    }

    public function getDateObject(): DateDTO {
        return new DateDTO($this->created_at);
    }


    public function getUsers(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @throws Exception
     */
    public function serviceFrontDTO(): ServiceFrontDTO
    {
        return new ServiceFrontDTO($this->hasOne(Services::class, ['id' => 'service_id'])->one());
    }



}