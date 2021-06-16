<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\BaseEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Enums\SexEnum;
use ZnKaz\Base\Domain\Exceptions\BadCenturyException;
use ZnKaz\Base\Domain\Helpers\IinDateHelper;

class IndividualParser implements ParserInterface
{

    private $dateParser;

    public function __construct()
    {
        $this->dateParser = new IndividualDateParser();
    }

    public function parse(string $value): BaseEntity
    {
        $birthday = $this->dateParser->parse($value);

        $individualEntity = new IndividualEntity();
        $individualEntity->setValue($value);
        $individualEntity->setSex($this->getSex($value));
        $individualEntity->setCentury(substr($value, 6, 1));
        $this->validateCentury($individualEntity->getCentury());
        $individualEntity->setBirthday($birthday);
        $individualEntity->setSerialNumber(substr($value, 7, 4));
        $individualEntity->setCheckSum(substr($value, 11, 1));
        return $individualEntity;
    }

    private function getSex(string $value)
    {
        $century = substr($value, 6, 1);
        return !empty($century % 2) ? SexEnum::MALE : SexEnum::FEMALE;
    }

    private function validateCentury($century): void
    {
        $maxCentury = $this->getMaxCentury();
        if ($century < 1 || $century > $maxCentury) {
            throw new BadCenturyException();
        }
    }

    private function getMaxCentury(): int
    {
        $nowYear = date('Y');
        $nowEpoch = substr($nowYear, 0, 2);
        return ($nowEpoch - 18 + 1) * 2;
    }
}
