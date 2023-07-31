<?php

namespace app\models;


use app\models\DTO\DateDTO;
use yii\db\ActiveRecord;

/**
 * @property integer $created_at
 * @property integer $status
 * @property integer $mode
 */
class Orders extends ActiveRecord
{
    public function getStatusToString(): string {
        switch ($this->status) {
            case "0":
                return 'Pending';
            case "1":
                return 'In progress';
            case "2":
                return 'Completed';
            case "3":
                return 'Canceled';
            case "4":
                return 'Failed';
            default:
                return 'not found';
        }
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


    public function getUsers()
    {
        return $this->hasMany(Users::class, ['id' => 'user_id']);
    }

}