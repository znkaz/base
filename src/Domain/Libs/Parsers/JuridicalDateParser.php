<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\DateEntity;
use ZnKaz\Base\Domain\Exceptions\BadDateException;

class JuridicalDateParser implements DateParserInterface
{

    public function parse(string $value): DateEntity
    {
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();
        $dateEntity->setDecade($smallYear);
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay( '01');

       $this->validateDate($dateEntity);
        return $dateEntity;
    }
    
    private function validateDate(DateEntity $dateEntity): void
    {
        $isValid = $dateEntity->getMonth() >= 1 && $dateEntity->getMonth() <= 12 && $dateEntity->getYear() >= 0 && $dateEntity->getYear() <= 99;
        if (!$isValid) {
            throw new BadDateException();
        }
    }
}
