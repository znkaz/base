<?php

namespace ZnKaz\Base\Domain\Entities;

class JuridicalEntity extends BaseEntity
{

    private $type;
    private $part;
    private $registrationDate;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getPart()
    {
        return $this->part;
    }

    public function setPart($part): void
    {
        $this->part = $part;
    }

    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate($registrationDate): void
    {
        $this->registrationDate = $registrationDate;
    }
}
