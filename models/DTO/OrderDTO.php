<?php

namespace app\models\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\ImmutableDataTransferObject;

class OrderDTO extends DataTransferObject
{


    public int $service_id;
    public int $id;
    public string $username;
    public int $quantity;
    public string $human_reed_status;
    public string $human_reed_mode;
    public string $link;
    public string $formatted_date_first;
    public string $formatted_date_second;
    public string $service_name;


    /**
     * @return DateDTO
     */
    public function getFormattedDate(): DateDTO
    {
        return $this->formatted_date;
    }


}