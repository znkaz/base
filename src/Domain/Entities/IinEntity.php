<?php

namespace ZnKaz\Base\Domain\Entities;

class IinEntity
{

    //private $value;
    private $type;
    private $juridical;
    private $individual;
    //private $serialNumber;
    //private $checkSum;
    //private $checkSumSequence;

    /*public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }*/

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getJuridical(): ?JuridicalEntity
    {
        return $this->juridical;
    }

    public function setJuridical(JuridicalEntity $juridical): void
    {
        $this->juridical = $juridical;
    }

    public function getIndividual(): ?IndividualEntity
    {
        return $this->individual;
    }

    public function setIndividual(IndividualEntity $individual): void
    {
        $this->individual = $individual;
    }

    /*public function getSerialNumber(): int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): void
    {
        $this->serialNumber = $serialNumber;
    }

    public function getCheckSum(): int
    {
        return $this->checkSum;
    }

    public function setCheckSum(int $checkSum): void
    {
        $this->checkSum = $checkSum;
    }

    public function getCheckSumSequence(): ?array
    {
        return $this->checkSumSequence;
    }

    public function setCheckSumSequence(array $checkSumSequence): void
    {
        $this->checkSumSequence = $checkSumSequence;
    }*/
}
