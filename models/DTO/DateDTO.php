<?php

namespace app\models\DTO;

use Carbon\Carbon;

class DateDTO
{

    public int $unixDate;
    public string $firstDate;
    public string $secondDate;

    /**
     * @param int $unixDate
     */
    public function __construct(int $unixDate)
    {
        $this->unixDate = $unixDate;
        $obj = Carbon::parse($this->unixDate);
        $this->firstDate = $obj->format('Y m d');
        $this->secondDate = $obj->format('H:i:s');
    }




}