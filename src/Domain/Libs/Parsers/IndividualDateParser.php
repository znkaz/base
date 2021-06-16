<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\DateEntity;

class IndividualDateParser implements DateParserInterface
{

    public function parse(string $value): DateEntity
    {
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();

        $century = substr($value, 6, 1);
        $epoch = self::getEpoch($century);
        $dateEntity->setYear($epoch . $smallYear);
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay(substr($value, 4, 2));

        if (!self::validateDate($dateEntity)) {
            throw new Exception('Birthday not valid');
        }
        return $dateEntity;
    }
    
    private static function validateDate(DateEntity $dateEntity): bool
    {
        return checkdate($dateEntity->getMonth(), $dateEntity->getDay(), $dateEntity->getYear());
    }

    private static function getEpoch(int $century): int
    {
        $residue = $century % 2;
        if ($residue == 0) {
            $century--;
        }
        $centuryDiv = floor($century / 2);
        return $centuryDiv + 18;
    }
}
