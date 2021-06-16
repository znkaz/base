<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnKaz\Base\Domain\Entities\IinEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Enums\SexEnum;
use ZnKaz\Base\Domain\Helpers\IinDateHelper;

class IndividualParser
{

    public function parse(string $value): IndividualEntity
    {
        try {
            $birthday = IinDateHelper::parseDateIndividual($value);
        } catch (\Exception $e) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('birthday', $e->getMessage());
            throw $unprocessibleEntityException;
        }
        
        $individualEntity = new IndividualEntity();
        $individualEntity->setSex(self::getSex($value));
        $individualEntity->setCentury(substr($value, 6, 1));
        self::validateCentury($individualEntity->getCentury());
        $individualEntity->setBirthday($birthday);
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
