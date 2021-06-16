<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnKaz\Base\Domain\Entities\BaseEntity;
use ZnKaz\Base\Domain\Entities\IinEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Enums\SexEnum;
use ZnKaz\Base\Domain\Helpers\IinDateHelper;

class IndividualParser implements ParserInterface
{

    public function parse(string $value): BaseEntity
    {
        $dateParser = new IndividualDateParser();
        $birthday = $dateParser->parse($value);
        
        $individualEntity = new IndividualEntity();
        $individualEntity->setValue($value);
        $individualEntity->setSex(self::getSex($value));
        $individualEntity->setCentury(substr($value, 6, 1));
        self::validateCentury($individualEntity->getCentury());
        $individualEntity->setBirthday($birthday);
        $individualEntity->setSerialNumber(substr($value, 7, 4));
        $individualEntity->setCheckSum(substr($value, 11, 1));
        return $individualEntity;
    }
    
    private static function getSex(string $value)
    {
        $century = substr($value, 6, 1);
        return !empty($century % 2) ? SexEnum::MALE : SexEnum::FEMALE;
    }

    private static function validateCentury($century): void
    {
        $maxCentury = self::getMaxCentury();
        if ($century < 1 || $century > $maxCentury) {
            throw new \Exception('Century not correct');
        }
    }

    private static function getMaxCentury(): int
    {
        $nowYear = date('Y');
        $nowEpoch = substr($nowYear, 0, 2);
        return ($nowEpoch - 18 + 1) * 2;
    }
}
