<?php

namespace ZnKaz\Base\Domain\Entities;

class IinEntity
{

    private $value;
    private $sex;
    private $serialNumber;
    private $check_sum;
    private $century;
    private $birthday;

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function setSex($sex): void
    {
        $this->sex = $sex;
    }

    public function getSerialNumber(): int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): void
    {
        $this->serialNumber = $serialNumber;
    }

    public function getCheckSum(): int
    {
        return $this->check_sum;
    }

    public function setCheckSum(int $check_sum): void
    {
        $this->check_sum = $check_sum;
    }

    public function getCentury(): int
    {
        return $this->century;
    }

    public function setCentury(int $century): void
    {
        $this->century = $century;
    }

    public function getBirthday(): DateEntity
    {
        return $this->birthday;
    }

    public function setBirthday(DateEntity $birthday): void
    {
        $this->birthday = $birthday;
    }
}
