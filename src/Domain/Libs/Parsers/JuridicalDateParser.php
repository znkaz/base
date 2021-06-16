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

        $isValidDate = $dateEntity->getMonth() >= 1 && $dateEntity->getMonth() <= 12 && $dateEntity->getYear() >= 0 && $dateEntity->getYear() <= 99;
//        $isValidDate = checkdate($dateEntity->getMonth(), '01', $dateEntity->getYear());
        if (!$isValidDate) {
            throw new BadDateException();
        }
        return $dateEntity;
    }
}
