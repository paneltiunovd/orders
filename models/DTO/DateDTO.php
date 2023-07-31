<?php

namespace app\models\DTO;

use Carbon\Carbon;

class DateDTO
{

    public int $unixDate;
    private string $firstDate;

    /**
     * @return string
     */
    public function getFirstDate(): string
    {
        return $this->firstDate;
    }

    /**
     * @return string
     */
    public function getSecondDate(): string
    {
        return $this->secondDate;
    }
    private string $secondDate;

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