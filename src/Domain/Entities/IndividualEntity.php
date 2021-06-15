<?php

namespace ZnKaz\Base\Domain\Entities;

class IndividualEntity
{

    private $sex;
    private $birthday;

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
}
