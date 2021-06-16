<?php

namespace ZnKaz\Base\Domain\Entities;

class IndividualEntity extends BaseEntity
{

    private $sex;
    private $birthday;
    private $century;

    public function getSex()
    {
        return $this->sex;
    }

    public function setSex($sex): void
    {
        $this->sex = $sex;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }
    
    public function getCentury(): ?int
    {
        return $this->century;
    }

    public function setCentury(int $century): void
    {
        $this->century = $century;
    }
}
