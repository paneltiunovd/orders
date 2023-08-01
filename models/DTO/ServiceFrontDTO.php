<?php

namespace app\models\DTO;

use app\models\Services;
use Exception;
use yii\db\ActiveRecord;

class ServiceFrontDTO
{

    public Services $service;
    private int $countOrders;
    private string $serviceName;

    /**
     * @param Services $service
     * @throws Exception
     */
    public function __construct(ActiveRecord $service)
    {
        $this->service = $service;
        $this->countOrders = $service->orders()->count();
        $this->serviceName = $service->name;
    }

    /**
     * @return int
     */
    public function getCountOrders(): int
    {
        return $this->countOrders;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }


}