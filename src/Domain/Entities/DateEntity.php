<?php

namespace ZnKaz\Base\Domain\Entities;

class DateEntity
{

    private $year;
    private $month;
    private $day;
    private $age;
    //private $century;

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /*public function getCentury(): int
    {
        return $this->century;
    }

    public function setCentury(int $century): void
    {
        $this->century = $century;
    }*/
}
