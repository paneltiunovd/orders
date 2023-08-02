<?php

namespace app\models;

use app\models\DTO\DateDTO;
use app\models\DTO\OrderDTO;
use app\models\Enums\OrderModeEnum;
use app\models\Enums\OrderStatusEnum;
use app\models\Enums\SearchTypeEnum;
use Spatie\DataTransferObject\ImmutableDataTransferObject;
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
 * @property mixed|null $service_id
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

    public function getUsers(): ActiveQuery
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    public function getService(): ActiveQuery
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    public function getDTO(): OrderDTO {
        $date = new DateDTO($this->created_at);
        return new OrderDTO(
            [
                'service_id' => $this->service_id,
                'service_name' => $this->service->name . ' ' . $this->quantity,
                'id' => $this->id,
                'username' => $this->users->getName() ?? '',
                'quantity' => $this->quantity,
                'human_reed_status' => $this->getStatusToString(),
                'human_reed_mode' => $this->getModeToString(),
                'link' => $this->link,
                'formatted_date_first' => $date->firstDate,
                'formatted_date_second' => $date->secondDate,
            ]
        );
    }



}