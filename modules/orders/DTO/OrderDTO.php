<?php

namespace app\modules\orders\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class OrderDTO extends DataTransferObject
{

    public int $id;
    public string $username;
    public string $link;
    public int $quantity;
    public string $human_reed_status;
    public string $human_reed_mode;
    public string $formatted_date_first;
    public string $formatted_date_second;
    public string $service_name;

}