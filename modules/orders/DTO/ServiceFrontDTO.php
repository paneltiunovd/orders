<?php

namespace app\modules\orders\DTO;

use app\modules\orders\models\Services;
use yii\db\ActiveRecord;

class ServiceFrontDTO
{

    private Services $service;
    private int $countOrders;
    private string $serviceName;
    /**
     * @var mixed|null
     */
    private $serviceId;

    /**
     * @param Services $service
     */
    public function __construct(ActiveRecord $service)
    {
        $this->service = $service;
        $this->countOrders = $service->getOrders()->count();
        $this->serviceName = $service->name;
        $this->serviceId = $service->id;
    }

    /**
     * @return mixed|null
     */
    public function getServiceId()
    {
        return $this->serviceId;
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