<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\BaseEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Enums\SexEnum;

class IndividualParser implements ParserInterface
{

    private $dateParser;

    public function __construct()
    {
        $this->dateParser = new IndividualDateParser();
    }

    public function parse(string $value): BaseEntity
    {
        $dateEntity = $this->dateParser->parse($value);

        $individualEntity = new IndividualEntity();
        $individualEntity->setValue($value);
        $individualEntity->setSex($this->getSex($value));
        $individualEntity->setCentury(substr($value, 6, 1));
        $individualEntity->setBirthday($dateEntity);
        $individualEntity->setSerialNumber(substr($value, 7, 4));
        $individualEntity->setCheckSum(substr($value, 11, 1));
        return $individualEntity;
    }

    private function getSex(string $value): string
    {
        $century = substr($value, 6, 1);
        return !empty($century % 2) ? SexEnum::MALE : SexEnum::FEMALE;
    }
}
